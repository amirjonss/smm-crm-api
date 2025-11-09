<?php

namespace App\Validator\Constraints;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ImageFileValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ImageFileValidator) {
            throw new UnexpectedTypeException($constraint, ImageFileValidator::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof File) {
            throw new UnexpectedValueException($value, File::class);
        }

        // Check file size
        if ($value->getSize() > $constraint->maxSize) {
            $this->context->buildViolation($constraint->maxSizeMessage)
                ->setParameter('{{ size }}', round($value->getSize() / 1024 / 1024, 2))
                ->setParameter('{{ limit }}', $constraint->maxSize / 1024 / 1024)
                ->addViolation();
            return;
        }

        // Check MIME type
        $mimeType = $value->getMimeType();
        if (!in_array($mimeType, $constraint->allowedMimeTypes, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ type }}', $mimeType)
                ->addViolation();
        }
    }
}
