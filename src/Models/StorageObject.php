<?php

namespace RaifuCore\Storage\Models;

use RaifuCore\Storage\Enums\StorageEnum;
use RaifuCore\Storage\Enums\StorageObjectTypeEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $size
 * @property StorageEnum|null $storage
 * @property StorageObjectTypeEnum|null $type
 * @property string $mime_type
 * @property string $origin_filename
 * @property string $directory
 * @property string $filename
 * @property string $created_at
 * @property string $updated_at
 */
class StorageObject extends Model
{
    protected $table = 'storage_objects';

    protected $casts = [
        'storage' => StorageEnum::class,
        'type' => StorageObjectTypeEnum::class,
    ];

    public function fileUrl(): string
    {
        $disks = config('filesystems.disks');

        $host = match ($this->storage) {
            StorageEnum::S3 => $disks['s3']['url'],
            StorageEnum::LOCAL => $disks['local']['root'],
            StorageEnum::PUBLIC => $disks['public']['url'],
        };
        $host = rtrim($host, '/') . '/';

        $path = ltrim($this->directory . '/' . $this->filename, '/');

        return $host . $path;
    }

    public function path(): string
    {
        return ltrim($this->directory . '/' . $this->filename, '/');
    }

    public function getFormattedFileSize(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
