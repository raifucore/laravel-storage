<?php

namespace RaifuCore\Storage\Actions;

use RaifuCore\Storage\Enums\StorageEnum;
use RaifuCore\Storage\Exceptions\FileDeleteException;
use RaifuCore\Storage\Requests\FileDeleteRequestDto;
use Illuminate\Support\Facades\Storage as CoreStorage;

class FileDeleteAction
{
    public function __construct(protected FileDeleteRequestDto $request) {}

    /**
     * @throws FileDeleteException
     */
    public function execute(): void
    {
        $driver = StorageEnum::driver($this->request->getStorageEnum());
        $disk = CoreStorage::disk($driver);
        if ($disk->exists($this->request->getFilepath())) {

            $disk->delete($this->request->getFilepath());

            if ($disk->exists($this->request->getFilepath())) {
                throw new FileDeleteException;
            }
        }
    }
}
