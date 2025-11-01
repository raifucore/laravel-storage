<?php

namespace RaifuCore\Storage\Actions;

use RaifuCore\Storage\Enums\StorageObjectTypeEnum;
use RaifuCore\Storage\Exceptions\FileDeleteException;
use RaifuCore\Storage\Exceptions\ObjectDeleteException;
use RaifuCore\Storage\Models\StorageObject;
use RaifuCore\Storage\Requests\FileDeleteRequestDto;
use Illuminate\Support\Facades\DB;

class ObjectDeleteAction
{
    protected string $mimeType;
    protected StorageObjectTypeEnum $type;
    protected string $directory;

    public function __construct(protected StorageObject $object) {}

    /**
     * @throws FileDeleteException
     * @throws ObjectDeleteException
     */
    public function execute(): void
    {
        DB::beginTransaction();

        $path = $this->object->path();
        $storage = $this->object->storage;

        // Instance
        try {
            $this->object->deleteOrFail();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new ObjectDeleteException($e->getMessage());
        }

        // Delete file from storage
        try {
            (new FileDeleteAction(
                new FileDeleteRequestDto($storage, $path)
            ))->execute();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new FileDeleteException($e->getMessage());
        }

        DB::commit();
    }
}
