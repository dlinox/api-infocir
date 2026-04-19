<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Services;

use App\Models\Learning\QuizOption;
use App\Modules\Learning\Instructor\Catalog\Course\Repositories\QuizOptionRepository;

class QuizOptionService
{
    public function __construct(
        private readonly QuizOptionRepository $repository
    ) {}

    public function save(array $data): QuizOption
    {
        return $this->repository->createOrUpdate($data);
    }

    public function delete(int $id): QuizOption
    {
        return $this->repository->delete($id);
    }
}
