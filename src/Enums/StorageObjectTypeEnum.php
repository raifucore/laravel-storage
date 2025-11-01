<?php

namespace RaifuCore\Storage\Enums;

enum StorageObjectTypeEnum: string
{
    case UNKNOWN = 'UNKNOWN';
    case IMAGE = 'IMAGE';
    case VIDEO = 'VIDEO';
    case AUDIO = 'AUDIO';
    case ARCHIVE = 'ARCHIVE';
    case DOCUMENT = 'DOCUMENT';
}
