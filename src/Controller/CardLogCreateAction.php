<?php

declare(strict_types=1);

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Component\CardLog\Enum\CardLogType;
use App\Component\User\CurrentUser;
use App\Controller\Base\AbstractController;
use App\Entity\CardLog;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class CardLogCreateAction extends AbstractController
{
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        CurrentUser $currentUser,
        NormalizerInterface $normalizer,
    ) {
        parent::__construct($serializer, $validator, $currentUser, $normalizer);
    }

    public function __invoke(CardLog $data): CardLog
    {
        $data->setType(CardLogType::COMMENT->value);

        return $data;
    }
}
