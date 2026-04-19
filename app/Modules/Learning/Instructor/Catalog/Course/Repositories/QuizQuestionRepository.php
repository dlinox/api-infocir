<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Repositories;

use App\Models\Learning\QuizQuestion;

class QuizQuestionRepository
{
    public function createOrUpdate(array $data): QuizQuestion
    {
        if (isset($data['id'])) {
            $question = QuizQuestion::findOrFail($data['id']);
            $question->update($data);
            return $question;
        }

        $data['order'] = QuizQuestion::where('lesson_id', $data['lesson_id'])->max('order') + 1;
        return QuizQuestion::create($data);
    }

    public function delete(int $id): QuizQuestion
    {
        $question = QuizQuestion::findOrFail($id);
        $question->delete();
        return $question;
    }
}
