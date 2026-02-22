<?php

declare(strict_types=1);

namespace App\Component\User\Exceptions;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthException extends UnauthorizedHttpException
{
    public function __construct($message = '')
    {
        parent::__construct($message, $message);
    }
}
