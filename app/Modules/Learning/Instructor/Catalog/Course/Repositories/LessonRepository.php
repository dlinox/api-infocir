<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Repositories;

use App\Models\Learning\Lesson;

class LessonRepository
{
    public function createOrUpdate(array $data): Lesson
    {
        if (isset($data['id'])) {
            $lesson = Lesson::findOrFail($data['id']);
            $lesson->update($data);
            return $lesson;
        }

        $data['order'] = Lesson::where('module_id', $data['module_id'])->max('order') + 1;

        return Lesson::create($data);
    }

    public function updateHasQuiz(int $id, bool $hasQuiz): Lesson
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->update(['has_quiz' => $hasQuiz]);

        if (!$hasQuiz) {
            $lesson->quizQuestions()->delete();
        }

        return $lesson->fresh();
    }

    public function delete(int $id): Lesson
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();
        return $lesson;
    }
}
