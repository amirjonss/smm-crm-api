<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Base\AbstractController;
use App\Entity\CardLog;

class TestLogTelegramAction extends AbstractController
{
    public function __invoke(): CardLog
    {
        throw new \RuntimeException('Test telegram error');
    }
}
