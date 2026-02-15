<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ImageFileConstraint extends Constraint
{
    public string $message = 'The file must be a valid image (JPEG, PNG, GIF, or WebP).';
    public string $maxSizeMessage = 'The file is too large ({{ size }} MB). Maximum allowed size is {{ limit }} MB.';
    public int $maxSize = 5 * 1024 * 1024;
    public array $allowedMimeTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
