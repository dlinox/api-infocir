<?php

namespace App\Common\Services\Actions;

use App\Common\Helpers\FileHelper;
use App\Models\Core\CoreFile;

class MoveFileAction
{
    public function execute(CoreFile $file, string $toDisk, string $folder): CoreFile
    {
        $newPath = FileHelper::moveFile($file->storage_disk, $toDisk, $file->filepath, $folder);

        $file->update([
            'storage_disk' => $toDisk,
            'filepath'     => $newPath,
        ]);

        return $file;
    }
}
