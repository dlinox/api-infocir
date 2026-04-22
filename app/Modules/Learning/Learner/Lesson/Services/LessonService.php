<?php

namespace App\Modules\Learning\Learner\Lesson\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Learning\Certification;
use App\Models\Learning\CourseModule;
use App\Models\Learning\Enrollment;
use App\Models\Learning\Lesson;
use App\Models\Learning\LessonProgress;
use App\Models\Learning\QuizAnswer;
use App\Models\Learning\QuizAttempt;
use App\Models\Learning\QuizOption;
use App\Models\Learning\QuizQuestion;
use App\Modules\Learning\Learner\Support\LearnerContext;
use Illuminate\Support\Facades\DB;

class LessonService
{
    public function __construct(
        private LearnerContext $learnerContext,
    ) {}

    public function completeLesson(int $enrollmentId, int $lessonId): array
    {
        $enrollment = $this->getEnrollment($enrollmentId);
        $lesson = $this->validateLessonInEnrollment($lessonId, $enrollment);

        LessonProgress::updateOrCreate(
            ['enrollment_id' => $enrollment->id, 'lesson_id' => $lesson->id],
            ['completed' => true, 'completed_at' => now()],
        );

        $this->recalculateProgress($enrollment);

        return [
            'lessonId'  => $lesson->id,
            'completed' => true,
            'progress'  => (float) $enrollment->fresh()->progress,
            'status'    => $enrollment->fresh()->status,
        ];
    }

    public function submitQuiz(int $enrollmentId, int $lessonId, array $answers): array
    {
        $enrollment = $this->getEnrollment($enrollmentId);
        $lesson = $this->validateLessonInEnrollment($lessonId, $enrollment);

        if (!$lesson->has_quiz) {
            throw new ApiException('Esta lección no tiene cuestionario.', 422);
        }

        $questions = QuizQuestion::where('lesson_id', $lesson->id)
            ->with('options')
            ->orderBy('order')
            ->get();

        if ($questions->isEmpty()) {
            throw new ApiException('Esta lección no tiene preguntas registradas.', 422);
        }

        $questionIds = $questions->pluck('id')->all();
        $submittedMap = [];
        foreach ($answers as $answer) {
            $qid = (int) ($answer['question_id'] ?? $answer['questionId'] ?? 0);
            $oid = (int) ($answer['option_id'] ?? $answer['optionId'] ?? 0);
            if ($qid && $oid) {
                $submittedMap[$qid] = $oid;
            }
        }

        foreach ($questionIds as $qid) {
            if (!isset($submittedMap[$qid])) {
                throw new ApiException('Debes responder todas las preguntas antes de enviar.', 422);
            }
        }

        $correct = 0;
        $results = [];

        foreach ($questions as $question) {
            $selectedOptionId = $submittedMap[$question->id];
            $selected = $question->options->firstWhere('id', $selectedOptionId);

            if (!$selected) {
                throw new ApiException("La opción seleccionada para una pregunta no es válida.", 422);
            }

            $isCorrect = (bool) $selected->is_correct;
            if ($isCorrect) $correct++;

            $correctOption = $question->options->firstWhere('is_correct', true);

            $results[] = [
                'questionId'        => $question->id,
                'selectedOptionId'  => $selected->id,
                'correctOptionId'   => $correctOption?->id,
                'isCorrect'         => $isCorrect,
                'explanation'       => $selected->explanation,
            ];
        }

        $score = round(($correct / $questions->count()) * 100, 2);
        $passed = $score >= (float) $lesson->passing_score;

        $attempt = DB::transaction(function () use ($enrollment, $lesson, $score, $passed, $questions, $submittedMap) {
            $attempt = QuizAttempt::create([
                'enrollment_id' => $enrollment->id,
                'lesson_id'     => $lesson->id,
                'score'         => $score,
                'passed'        => $passed,
                'attempted_at'  => now(),
            ]);

            $rows = [];
            foreach ($questions as $question) {
                $rows[] = [
                    'attempt_id'  => $attempt->id,
                    'question_id' => $question->id,
                    'option_id'   => $submittedMap[$question->id],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
            QuizAnswer::insert($rows);

            return $attempt;
        });

        LessonProgress::updateOrCreate(
            ['enrollment_id' => $enrollment->id, 'lesson_id' => $lesson->id],
            ['completed' => true, 'completed_at' => now()],
        );
        $this->recalculateProgress($enrollment);

        return [
            'attemptId'   => $attempt->id,
            'score'       => $score,
            'passed'      => true,
            'correct'     => $correct,
            'total'       => $questions->count(),
            'results'     => $results,
            'progress'    => (float) $enrollment->fresh()->progress,
            'status'      => $enrollment->fresh()->status,
        ];
    }

    private function getEnrollment(int $enrollmentId): Enrollment
    {
        $workerId = $this->learnerContext->workerId();

        $enrollment = Enrollment::where('id', $enrollmentId)
            ->where('worker_id', $workerId)
            ->first();

        if (!$enrollment) {
            throw new ApiException('Inscripción no encontrada.', 404);
        }

        return $enrollment;
    }

    private function validateLessonInEnrollment(int $lessonId, Enrollment $enrollment): Lesson
    {
        $lesson = Lesson::find($lessonId);
        if (!$lesson) {
            throw new ApiException('Lección no encontrada.', 404);
        }

        $courseId = match ($enrollment->enrollable_type) {
            'learning_courses'   => $enrollment->enrollable_id,
            'learning_trainings' => $enrollment->enrollable?->course_id,
            default              => null,
        };

        if (!$courseId) {
            throw new ApiException('Esta inscripción no tiene contenido.', 422);
        }

        $moduleBelongsToCourse = CourseModule::where('id', $lesson->module_id)
            ->where('course_id', $courseId)
            ->exists();

        if (!$moduleBelongsToCourse) {
            throw new ApiException('La lección no pertenece a tu curso.', 403);
        }

        return $lesson;
    }

    private function recalculateProgress(Enrollment $enrollment): void
    {
        $courseId = match ($enrollment->enrollable_type) {
            'learning_courses'   => $enrollment->enrollable_id,
            'learning_trainings' => $enrollment->enrollable?->course_id,
            default              => null,
        };

        if (!$courseId) return;

        $totalLessons = Lesson::whereHas('module', fn ($q) => $q->where('course_id', $courseId)->where('is_active', true))
            ->where('is_active', true)
            ->count();

        if ($totalLessons === 0) return;

        $completed = LessonProgress::where('enrollment_id', $enrollment->id)
            ->where('completed', true)
            ->count();

        $progress = round(($completed / $totalLessons) * 100, 2);
        $isCompleted = $completed >= $totalLessons;

        $enrollment->progress = $progress;
        $enrollment->status = $isCompleted
            ? 'completed'
            : ($progress > 0 ? 'in_progress' : $enrollment->status);
        if ($isCompleted && !$enrollment->completed_at) {
            $enrollment->completed_at = now();
        }
        $enrollment->save();

        if ($isCompleted) {
            $this->issueCertificationIfApplicable($enrollment);
        }
    }

    private function issueCertificationIfApplicable(Enrollment $enrollment): void
    {
        if (Certification::where('enrollment_id', $enrollment->id)->exists()) {
            return;
        }

        $templateId = null;
        if ($enrollment->enrollable_type === 'learning_courses') {
            $templateId = $enrollment->enrollable?->certificate_template_id;
        } elseif ($enrollment->enrollable_type === 'learning_trainings') {
            $training = $enrollment->enrollable;
            $templateId = $training?->certificate_template_id ?? $training?->course?->certificate_template_id;
        }

        if (!$templateId) return;

        Certification::create([
            'enrollment_id'      => $enrollment->id,
            'template_id'        => $templateId,
            'certificate_number' => $this->generateCertificateNumber(),
            'issued_at'          => now()->toDateString(),
        ]);
    }

    private function generateCertificateNumber(): string
    {
        do {
            $number = 'CERT-' . now()->format('Y') . '-' . strtoupper(bin2hex(random_bytes(4)));
        } while (Certification::where('certificate_number', $number)->exists());

        return $number;
    }
}
