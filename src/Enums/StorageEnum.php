<?php

namespace RaifuCore\Storage\Enums;

enum StorageEnum: string
{
    case S3 = 'S3';
    case LOCAL = 'LOCAL';

    case PUBLIC = 'PUBLIC';

    public static function driver(self $storage): string
    {
        return match ($storage) {
            StorageEnum::S3 => 's3',
            StorageEnum::LOCAL => 'local',
            StorageEnum::PUBLIC => 'public',
        };
    }
}
