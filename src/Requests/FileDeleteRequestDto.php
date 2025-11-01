<?php

namespace RaifuCore\Storage\Requests;

use RaifuCore\Storage\Enums\StorageEnum;

class FileDeleteRequestDto
{
    public function __construct(
        protected StorageEnum $storageEnum,
        protected string $filepath,
    ) {}

    public function getStorageEnum(): StorageEnum
    {
        return $this->storageEnum;
    }

    public function getFilepath(): string
    {
        return $this->filepath;
    }
}
