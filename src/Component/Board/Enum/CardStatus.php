<?php

namespace App\Component\Board\Enum;

enum CardStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case REVIEW = 'review';
    case DONE = 'done';

    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
