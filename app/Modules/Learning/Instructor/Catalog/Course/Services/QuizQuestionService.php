<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Services;

use App\Models\Learning\QuizQuestion;
use App\Modules\Learning\Instructor\Catalog\Course\Repositories\QuizQuestionRepository;

class QuizQuestionService
{
    public function __construct(
        private readonly QuizQuestionRepository $repository
    ) {}

    public function save(array $data): QuizQuestion
    {
        return $this->repository->createOrUpdate($data);
    }

    public function delete(int $id): QuizQuestion
    {
        return $this->repository->delete($id);
    }
}
