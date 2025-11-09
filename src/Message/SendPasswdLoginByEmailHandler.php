<?php

namespace App\Message;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
readonly class SendPasswdLoginByEmailHandler
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function __invoke(SendPasswdLoginByEmail $message)
    {
        $email = new Email();
        $email
            ->from('khagencyai@gmail.com')
            ->to($message->getEmail())
            ->subject('Password and Login')
            ->html('login: ' . $message->getEmail() . '<br>' . 'password: ' . $message->getPassword());
        $this->mailer->send($email);
    }
}
