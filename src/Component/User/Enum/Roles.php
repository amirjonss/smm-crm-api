<?php

namespace App\Component\User\Enum;

enum Roles: string
{
    case SMM = 'ROLE_SMM';
    case EDITOR = 'ROLE_EDITOR';
    case DESIGNER = 'ROLE_DESIGNER';
    case OPERATOR = 'ROLE_OPERATOR';
    case ADMIN = 'ROLE_ADMIN';
    case USER = 'ROLE_USER';

    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
