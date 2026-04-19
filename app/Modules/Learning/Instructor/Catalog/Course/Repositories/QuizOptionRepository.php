<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Repositories;

use App\Models\Learning\QuizOption;

class QuizOptionRepository
{
    public function createOrUpdate(array $data): QuizOption
    {
        if (isset($data['id'])) {
            $option = QuizOption::findOrFail($data['id']);
            $option->update($data);
        } else {
            $data['order'] = QuizOption::where('question_id', $data['question_id'])->max('order') + 1;
            $option = QuizOption::create($data);
        }

        // Solo puede haber una respuesta correcta por pregunta
        if (!empty($data['is_correct'])) {
            QuizOption::where('question_id', $option->question_id)
                ->where('id', '!=', $option->id)
                ->update(['is_correct' => false, 'explanation' => null]);
        }

        return $option->fresh();
    }

    public function delete(int $id): QuizOption
    {
        $option = QuizOption::findOrFail($id);
        $option->delete();
        return $option;
    }
}
