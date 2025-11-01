<?php

namespace RaifuCore\Storage\Actions;

use RaifuCore\Storage\Dto\ObjectDto;
use RaifuCore\Storage\Enums\StorageEnum;
use RaifuCore\Storage\Exceptions\FileUploadException;
use RaifuCore\Storage\Requests\FileUploadRequestDto;
use Illuminate\Support\Facades\Storage as CoreStorage;

class FileUploadAction
{
    public function __construct(protected FileUploadRequestDto $request) {}

    /**
     * @throws FileUploadException
     */
    public function execute(): ObjectDto
    {
        $driver = StorageEnum::driver($this->request->getStorageEnum());

        try {
            $storagePath = $this->request->getUploadedFile()
                ->storeAs(
                    $this->request->getDirectory(),
                    $this->request->getFilename(),
                    $driver
                );
            if (!$storagePath) {
                throw new \Exception('Can\'t upload file to the storage');
            }

            $disk = CoreStorage::disk($driver);
            if (!$disk->exists($storagePath)) {
                throw new \Exception('Object not found in the storage after uploading');
            }

            return (new ObjectDto)
                ->setExists(true)
                ->setPath($storagePath)
                ->setDirectory($this->request->getDirectory())
                ->setFilename($this->request->getFilename())
                ->setSize($this->request->getUploadedFile()->getSize())
                ->setStorage($driver);

        } catch (\Throwable $e) {
            throw new FileUploadException($e->getMessage());
        }
    }
}
