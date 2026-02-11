<?php

namespace App\Component\Board\Enum;

enum BoardMemberRole: string
{
    case OWNER = 'owner';
    case EDITOR = 'editor';
    case VIEWER = 'viewer';

    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
