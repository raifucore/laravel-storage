<?php

namespace RaifuCore\Storage;

use RaifuCore\Storage\Actions\ObjectCreateAction;
use RaifuCore\Storage\Actions\ObjectDeleteAction;
use RaifuCore\Storage\Exceptions\FileDeleteException;
use RaifuCore\Storage\Exceptions\FileUploadException;
use RaifuCore\Storage\Exceptions\ObjectCreateException;
use RaifuCore\Storage\Exceptions\ObjectDeleteException;
use RaifuCore\Storage\Models\StorageObject;
use RaifuCore\Storage\Requests\ObjectCreateRequestDto;

class StorageModule
{
    /**
     * @throws FileDeleteException
     * @throws FileUploadException
     * @throws ObjectCreateException
     */
    public static function create(ObjectCreateRequestDto $dto): StorageObject
    {
        return (new ObjectCreateAction($dto))->execute();
    }

    /**
     * @throws FileDeleteException
     * @throws ObjectDeleteException
     */
    public static function delete(StorageObject $object): void
    {
        (new ObjectDeleteAction($object))->execute();
    }
}
