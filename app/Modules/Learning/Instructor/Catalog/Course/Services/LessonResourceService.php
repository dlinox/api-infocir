<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Services;

use App\Common\Services\FileService;
use App\Models\Core\CoreFile;
use App\Models\Learning\LessonResource;
use App\Modules\Learning\Instructor\Catalog\Course\Repositories\LessonResourceRepository;

class LessonResourceService
{
    public function __construct(
        private readonly LessonResourceRepository $repository,
        private readonly FileService $fileService,
    ) {}

    public function save(array $data): LessonResource
    {
        $resource = $this->repository->createOrUpdate($data);

        if ($resource->file_id) {
            $file = CoreFile::find($resource->file_id);
            if ($file && $file->storage_disk === 'temp') {
                $this->fileService->moveToStorage($resource->file_id, 'learning', 'courses/resources');
            }
        }

        return $resource;
    }

    public function delete(int $id): LessonResource
    {
        $resource = $this->repository->findById($id);

        if ($resource->file_id) {
            $this->fileService->delete($resource->file_id);
        }

        return $this->repository->delete($id);
    }
}
