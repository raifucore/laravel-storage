<?php

namespace RaifuCore\Storage\Requests;

use RaifuCore\Storage\Enums\StorageEnum;
use Illuminate\Http\UploadedFile;

class FileUploadRequestDto
{
    public function __construct(
        protected StorageEnum $storageEnum,
        protected UploadedFile $uploadedFile,
        protected string $filename,
        protected string $directory,
    ) {}

    public function getStorageEnum(): StorageEnum
    {
        return $this->storageEnum;
    }

    public function getUploadedFile(): UploadedFile
    {
        return $this->uploadedFile;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }
}
