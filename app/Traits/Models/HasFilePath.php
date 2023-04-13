<?php

namespace App\Traits\Models;

use App\Services\FileService;

trait HasFilePath
{
    public function getTemporaryPathAttribute(): string
    {
        $fileService = new FileService();
        $lifetime = config('s3.file_lifetime');
        $path = $this->storage_path;

        return $fileService->getTemporaryUrl($path, $lifetime);
    }

    public function getPathAttribute(): string
    {
        $fileService = new FileService();
        $path = $this->storage_path;

        return $fileService->getFileFromDisk($path);
    }

}
