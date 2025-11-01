<?php

namespace RaifuCore\Storage\Requests;

use RaifuCore\Storage\Enums\StorageEnum;
use Illuminate\Http\UploadedFile;

class ObjectCreateRequestDto
{
    protected string|null $directory = null;

    public function __construct(
        protected StorageEnum  $storage,
        protected UploadedFile $uploadedFile,
    ) {}

    public function getStorage(): StorageEnum
    {
        return $this->storage;
    }

    public function getUploadedFile(): UploadedFile
    {
        return $this->uploadedFile;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(?string $directory): self
    {
        $this->directory = !is_null($directory) ? mb_strtolower($directory) : null;
        return $this;
    }
}
