<?php

namespace App\Common\Services\Actions;

use App\Common\Helpers\FileHelper;
use App\Models\Core\CoreFile;

class DeleteFileAction
{
    public function execute(CoreFile $file): void
    {
        FileHelper::deleteFile($file->storage_disk, $file->filepath);
        $file->delete();
    }
}
