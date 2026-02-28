<?php

namespace App\MessageHandler;

use App\Message\SendPasswdLoginByEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
readonly class SendPasswdLoginByEmailHandler
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function __invoke(SendPasswdLoginByEmail $message)
    {
        $email = new Email();
        $email
            ->from('khsystem@mail.ru')
            ->to($message->getEmail())
            ->subject('Password and Login')
            ->html('login: ' . $message->getEmail() . '<br>password: ' . $message->getPassword());
        $this->mailer->send($email);
    }
}
