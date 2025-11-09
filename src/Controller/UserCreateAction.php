<?php

declare(strict_types=1);

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Component\User\CurrentUser;
use App\Component\User\UserFactory;
use App\Component\User\UserManager;
use App\Controller\Base\AbstractController;
use App\Entity\User;
use App\Message\SendPasswdLoginByEmail;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CreateUserController
 *
 * @package App\Controller
 */
class UserCreateAction extends AbstractController
{
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        CurrentUser $currentUser,
        private MessageBusInterface $messageBus
    ) {
        parent::__construct($serializer, $validator, $currentUser);
    }

    public function __invoke(
        User $data,
        UserFactory $userFactory,
        UserManager $userManager,
        UserRepository $userRepository,
    ): User {
        $this->validate($data);

        if ($userRepository->findOneByEmail($data->getEmail())) {
            throw new BadRequestHttpException('Email already taken');
        }

        if ($data->getPassword() === null) {
            $generatedPassword = $this->generatePassword();
            $data->setPassword($generatedPassword);
            $this->sendEmail($data);
        }

        $user = $userFactory->create(
            $data->getEmail(),
            $data->getPassword(),
            $data->getGivenName(),
            $data->getFamilyName()
        );
        $userManager->save($user, true);

        return $user;
    }

    private function generatePassword(): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i = 0; $i < 12; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }

    private function sendEmail(User $data): void
    {
        $message = new SendPasswdLoginByEmail($data->getEmail(), $data->getPassword());
        $this->messageBus->dispatch($message);
    }
}
