<?php

namespace RaifuCore\Storage\Actions;

use RaifuCore\Storage\Enums\StorageEnum;
use RaifuCore\Storage\Enums\StorageObjectTypeEnum;
use RaifuCore\Storage\Exceptions\FileDeleteException;
use RaifuCore\Storage\Exceptions\FileUploadException;
use RaifuCore\Storage\Exceptions\ObjectCreateException;
use RaifuCore\Storage\Models\StorageObject;
use RaifuCore\Storage\Requests\FileDeleteRequestDto;
use RaifuCore\Storage\Requests\FileUploadRequestDto;
use RaifuCore\Storage\Requests\ObjectCreateRequestDto;

class ObjectCreateAction
{
    protected string $mimeType;
    protected StorageObjectTypeEnum $type;
    protected string $directory;

    public function __construct(protected ObjectCreateRequestDto $dto) {}

    /**
     * @throws FileUploadException
     * @throws ObjectCreateException
     * @throws FileDeleteException
     */
    public function execute(): StorageObject
    {
        $this->_detectMimeType();
        $this->_detectObjectType();
        $this->_detectDirectory();

        // Upload file to a storage
        $remoteObjectDto = (new FileUploadAction(
            new FileUploadRequestDto(
                $this->dto->getStorage(),
                $this->dto->getUploadedFile(),
                $this->_filename(),
                $this->directory,
            )
        ))->execute();

        // Instance
        $object = new StorageObject;
        $object->size = $remoteObjectDto->getSize();
        $object->storage = StorageEnum::tryFrom(strtoupper($remoteObjectDto->getStorage()));
        $object->type = $this->type;
        $object->mime_type = $this->mimeType;
        $object->origin_filename = $this->dto->getUploadedFile()->getClientOriginalName();
        $object->directory = $remoteObjectDto->getDirectory();
        $object->filename = $remoteObjectDto->getFilename();
        try {
            $object->saveOrFail();
            return $object;
        } catch (\Throwable $e) {

            // Delete file from storage
            (new FileDeleteAction(
                new FileDeleteRequestDto($this->dto->getStorage(), $remoteObjectDto->getPath())
            ))->execute();

            throw new ObjectCreateException($e->getMessage());
        }
    }

    private function _detectMimeType(): void
    {
        $this->mimeType = $this->dto->getUploadedFile()->getMimeType();
    }

    private function _detectObjectType(): void
    {
        if (str_starts_with($this->mimeType, 'image/')) {
            $this->type = StorageObjectTypeEnum::IMAGE;
        } else if (str_starts_with($this->mimeType, 'video/')) {
            $this->type = StorageObjectTypeEnum::VIDEO;
        } else if (str_starts_with($this->mimeType, 'audio/')) {
            $this->type = StorageObjectTypeEnum::AUDIO;
        } else if (in_array($this->mimeType, [
            'application/zip',
            'application/gzip',
            'application/x-tar',
            'application/vnd.rar',
            'application/x-rar',
        ], true)) {
            $this->type = StorageObjectTypeEnum::ARCHIVE;
        } else if (in_array($this->mimeType, [
            'text/plain',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ], true)) {
            $this->type = StorageObjectTypeEnum::DOCUMENT;
        } else {
            $this->type = StorageObjectTypeEnum::UNKNOWN;
        }
    }

    private function _detectDirectory(): void
    {
        $directorySegments = array_filter([
            $this->dto->getDirectory(),
            strtolower($this->type->value),
        ]);
        $this->directory = implode('/', $directorySegments);
    }

    private function _filename(): string
    {
        return sprintf(
            '%s_%s.%s',
            date('Ymd_Hi'),
            Str::uuid()->toString(),
            mb_strtolower($this->dto->getUploadedFile()->getClientOriginalExtension())
        );
    }
}
