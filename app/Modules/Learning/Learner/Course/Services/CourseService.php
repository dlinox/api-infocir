<?php

namespace App\Modules\Learning\Learner\Course\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Learning\Course;
use App\Models\Learning\Enrollment;
use App\Models\Learning\LessonProgress;
use App\Models\Learning\QuizAttempt;
use App\Modules\Learning\Learner\Support\LearnerContext;

class CourseService
{
    public function __construct(
        private LearnerContext $learnerContext,
    ) {}

    public function getCatalog(): array
    {
        $workerId = $this->learnerContext->workerId();

        $enrolledCourseIds = Enrollment::where('worker_id', $workerId)
            ->where('enrollable_type', 'learning_courses')
            ->pluck('enrollable_id')
            ->toArray();

        $courses = Course::where('status', 'published')
            ->with(['area:id,name', 'coverImageFile'])
            ->withCount('modules')
            ->orderBy('name')
            ->get();

        return $courses->map(function (Course $course) use ($enrolledCourseIds) {
            return [
                'id'           => $course->id,
                'name'         => $course->name,
                'description'  => $course->description,
                'durationMin'  => $course->duration_min,
                'area'         => $course->area ? ['id' => $course->area->id, 'name' => $course->area->name] : null,
                'coverImage'   => $course->coverImageFile ? [
                    'id'       => $course->coverImageFile->id,
                    'url'      => $course->coverImageFile->url,
                    'filename' => $course->coverImageFile->filename,
                ] : null,
                'modulesCount' => $course->modules_count,
                'isEnrolled'   => in_array($course->id, $enrolledCourseIds, true),
            ];
        })->values()->all();
    }

    public function getContent(int $enrollmentId): array
    {
        $workerId = $this->learnerContext->workerId();

        $enrollment = Enrollment::where('id', $enrollmentId)
            ->where('worker_id', $workerId)
            ->first();

        if (!$enrollment) {
            throw new ApiException('Inscripción no encontrada.', 404);
        }

        $courseId = $this->resolveCourseId($enrollment);
        if (!$courseId) {
            throw new ApiException('Esta inscripción no tiene contenido disponible.', 422);
        }

        $course = Course::with([
            'area:id,name',
            'coverImageFile',
            'modules' => fn ($q) => $q->where('is_active', true)->orderBy('order'),
            'modules.lessons' => fn ($q) => $q->where('is_active', true)->orderBy('order'),
            'modules.lessons.resources' => fn ($q) => $q->where('is_active', true)->orderBy('order'),
            'modules.lessons.resources.file',
            'modules.lessons.quizQuestions' => fn ($q) => $q->orderBy('order'),
            'modules.lessons.quizQuestions.options' => fn ($q) => $q->orderBy('order'),
        ])->find($courseId);

        if (!$course) {
            throw new ApiException('El curso no existe o fue eliminado.', 404);
        }

        $completedLessonIds = LessonProgress::where('enrollment_id', $enrollment->id)
            ->where('completed', true)
            ->pluck('lesson_id')
            ->all();

        $quizAttempts = QuizAttempt::where('enrollment_id', $enrollment->id)
            ->get()
            ->groupBy('lesson_id');

        $totalLessons = 0;
        $completedCount = 0;

        $modules = $course->modules->map(function ($module) use ($completedLessonIds, $quizAttempts, &$totalLessons, &$completedCount) {
            return [
                'id'          => $module->id,
                'title'       => $module->title,
                'description' => $module->description,
                'order'       => $module->order,
                'lessons'     => $module->lessons->map(function ($lesson) use ($completedLessonIds, $quizAttempts, &$totalLessons, &$completedCount) {
                    $totalLessons++;
                    $isCompleted = in_array($lesson->id, $completedLessonIds, true);
                    if ($isCompleted) $completedCount++;

                    $bestAttempt = $quizAttempts->get($lesson->id)?->sortByDesc('score')->first();

                    return [
                        'id'           => $lesson->id,
                        'title'        => $lesson->title,
                        'description'  => $lesson->description,
                        'order'        => $lesson->order,
                        'hasQuiz'      => (bool) $lesson->has_quiz,
                        'passingScore' => (float) $lesson->passing_score,
                        'completed'    => $isCompleted,
                        'bestScore'    => $bestAttempt ? (float) $bestAttempt->score : null,
                        'quizPassed'   => $bestAttempt ? (bool) $bestAttempt->passed : false,
                        'resources'    => $lesson->resources->map(fn ($r) => [
                            'id'    => $r->id,
                            'type'  => $r->type,
                            'title' => $r->title,
                            'body'  => $r->body,
                            'order' => $r->order,
                            'file'  => $r->file ? [
                                'id'       => $r->file->id,
                                'url'      => $r->file->url,
                                'filename' => $r->file->filename,
                                'mimeType' => $r->file->mime_type,
                            ] : null,
                        ])->values(),
                        'questions'    => $lesson->quizQuestions->map(fn ($q) => [
                            'id'       => $q->id,
                            'question' => $q->question,
                            'hint'     => $q->hint,
                            'order'    => $q->order,
                            'options'  => $q->options->map(fn ($o) => [
                                'id'          => $o->id,
                                'text'        => $o->text,
                                'order'       => $o->order,
                                'isCorrect'   => (bool) $o->is_correct,
                                'explanation' => $o->explanation,
                            ])->values(),
                        ])->values(),
                    ];
                })->values(),
            ];
        })->values();

        return [
            'enrollment' => [
                'id'         => $enrollment->id,
                'status'     => $enrollment->status,
                'progress'   => (float) $enrollment->progress,
                'enrolledAt' => $enrollment->enrolled_at?->toIso8601String(),
            ],
            'course' => [
                'id'          => $course->id,
                'name'        => $course->name,
                'description' => $course->description,
                'durationMin' => $course->duration_min,
                'area'        => $course->area ? ['id' => $course->area->id, 'name' => $course->area->name] : null,
                'coverImage'  => $course->coverImageFile ? [
                    'id'       => $course->coverImageFile->id,
                    'url'      => $course->coverImageFile->url,
                    'filename' => $course->coverImageFile->filename,
                ] : null,
            ],
            'modules'        => $modules,
            'totalLessons'   => $totalLessons,
            'completedLessons' => $completedCount,
        ];
    }

    private function resolveCourseId(Enrollment $enrollment): ?int
    {
        return match ($enrollment->enrollable_type) {
            'learning_courses'   => $enrollment->enrollable_id,
            'learning_trainings' => $enrollment->enrollable?->course_id,
            default              => null,
        };
    }
}
