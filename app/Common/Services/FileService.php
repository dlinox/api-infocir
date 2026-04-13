<?php

namespace App\Common\Services;

use App\Common\Services\Actions\CreateFileAction;
use App\Common\Services\Actions\CreateFileFromBase64Action;
use App\Common\Services\Actions\DeleteFileAction;
use App\Common\Services\Actions\MoveFileAction;
use App\Models\Core\CoreFile;
use Illuminate\Http\UploadedFile;

class FileService
{
    public function __construct(
        private CreateFileAction $createFileAction,
        private CreateFileFromBase64Action $createFileFromBase64Action,
        private DeleteFileAction $deleteFileAction,
        private MoveFileAction $moveFileAction,
    ) {}

    public function upload(UploadedFile $file, ?string $caption = null): CoreFile
    {
        return $this->createFileAction->execute($file, $caption);
    }

    public function uploadBase64(string $base64, ?string $caption = null): CoreFile
    {
        return $this->createFileFromBase64Action->execute($base64, $caption);
    }

    public function delete(int $id): void
    {
        $file = CoreFile::findOrFail($id);
        $this->deleteFileAction->execute($file);
    }

    public function moveToStorage(int $fileId, string $disk, string $folder): CoreFile
    {
        $file = CoreFile::findOrFail($fileId);
        return $this->moveFileAction->execute($file, $disk, $folder);
    }
}
