<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Services;

use App\Modules\Learning\Instructor\Catalog\Course\Repositories\LessonRepository;
use App\Modules\Learning\Instructor\Catalog\Course\Repositories\QuizQuestionRepository;
use App\Modules\Learning\Instructor\Catalog\Course\Repositories\QuizOptionRepository;

class LessonService
{
    public function __construct(
        private LessonRepository $lessonRepository,
        private QuizQuestionRepository $quizQuestionRepository,
        private QuizOptionRepository $quizOptionRepository,
    ) {}

    public function save(array $data)
    {
        $isNew = !isset($data['id']);
        $lesson = $this->lessonRepository->createOrUpdate($data);

        if ($isNew && ($lesson->has_quiz ?? false)) {
            $question = $this->quizQuestionRepository->createOrUpdate([
                'lesson_id' => $lesson->id,
                'question'  => 'Pregunta 1',
            ]);

            $this->quizOptionRepository->createOrUpdate([
                'question_id' => $question->id,
                'text'        => 'Opción 1',
                'is_correct'  => false,
            ]);
        }

        return $lesson;
    }

    public function updateHasQuiz(int $id, bool $hasQuiz)
    {
        $lesson = $this->lessonRepository->updateHasQuiz($id, $hasQuiz);

        if ($hasQuiz && $lesson->quizQuestions()->count() === 0) {
            $question = $this->quizQuestionRepository->createOrUpdate([
                'lesson_id' => $lesson->id,
                'question'  => 'Pregunta 1',
            ]);

            $this->quizOptionRepository->createOrUpdate([
                'question_id' => $question->id,
                'text'        => 'Opción 1',
                'is_correct'  => false,
            ]);
        }

        return $lesson;
    }

    public function delete(int $id)
    {
        return $this->lessonRepository->delete($id);
    }
}
