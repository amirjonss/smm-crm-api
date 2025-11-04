<?php

namespace App\Controller;

use App\Controller\Base\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class TestAction extends AbstractController
{
    public function __invoke(MailerInterface $mailer)
    {
        $email = new Email();
        $email
            ->from('khagencyai@gmail.com')
            ->to('fayzullayev.amir@gmail.com')
            ->subject('Hello')
            ->html('<h1> One moment </h1>');
        $mailer->send($email);
        exit();
    }
}
