<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ImageFileConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ImageFileConstraint) {
            throw new UnexpectedTypeException($constraint, ImageFileConstraint::class);
        }

        if ($value === null) {
            return;
        }

        if (!$value instanceof File) {
            throw new UnexpectedValueException($value, File::class);
        }

        if ($value->getSize() > $constraint->maxSize) {
            $this->context->buildViolation($constraint->maxSizeMessage)
                ->setParameter('{{ size }}', (string) round($value->getSize() / 1024 / 1024, 2))
                ->setParameter('{{ limit }}', (string) ($constraint->maxSize / 1024 / 1024))
                ->addViolation();
            return;
        }

        $mimeType = $value->getMimeType();
        if (!in_array($mimeType, $constraint->allowedMimeTypes, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ type }}', (string) $mimeType)
                ->addViolation();
        }
    }
}
