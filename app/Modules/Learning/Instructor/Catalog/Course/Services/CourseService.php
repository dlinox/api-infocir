<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Common\Helpers\FileHelper;
use App\Common\Services\FileService;
use App\Models\Core\CoreFile;
use App\Modules\Learning\Instructor\Catalog\Course\Repositories\CourseRepository;

class CourseService
{
    public function __construct(
        private CourseRepository $courseRepository,
        private FileService $fileService,
    ) {}

    public function dataTable(Request $request)
    {
        return $this->courseRepository->dataTable($request);
    }

    public function findById(int $id)
    {
        return $this->courseRepository->findById($id);
    }

    public function save(array $data)
    {
        return $this->courseRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->courseRepository->delete($id);
    }

    public function uploadCover(int $courseId, UploadedFile $file, ?string $caption = null)
    {
        $course = $this->courseRepository->findById($courseId);

        if ($course->cover_image) {
            $this->fileService->delete($course->cover_image);
        }

        $extension = $file->getClientOriginalExtension();
        $filename = $caption
            ? FileHelper::sanitizeFilename($caption, $extension)
            : FileHelper::generateUniqueFilename('cover', $extension);
        $filepath = 'courses/covers/' . $filename;

        FileHelper::saveFile($file->get(), $filepath, 'learning');

        $coreFile = CoreFile::create([
            'storage_disk' => 'learning',
            'filename'     => $filename,
            'filepath'     => $filepath,
            'mime_type'    => $file->getMimeType(),
            'caption'      => $caption,
            'size'         => $file->getSize(),
        ]);

        $course->update(['cover_image' => $coreFile->id]);

        return $coreFile;
    }

    public function getSelectItems()
    {
        return $this->courseRepository->getSelectItems();
    }
}
