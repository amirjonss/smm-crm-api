<?php

declare(strict_types=1);

namespace App\Enum;

enum ReportType: string
{
    case TODAY = 'today';
    case YESTERDAY = 'yesterday';

    /**
     * @return array{from: \DateTimeImmutable, to: \DateTimeImmutable}
     */
    public function getDateRange(): array
    {
        return match ($this) {
            self::TODAY => [
                'from' => new \DateTimeImmutable('today 00:00:00'),
                'to' => new \DateTimeImmutable('today 23:59:59'),
            ],
            self::YESTERDAY => [
                'from' => new \DateTimeImmutable('yesterday 00:00:00'),
                'to' => new \DateTimeImmutable('yesterday 23:59:59'),
            ],
        };
    }
}
