<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;

class ContentPlanWebhookAction extends AbstractController
{
    #[Route('/api/webhook/content-plans/send-daily', name: 'webhook_send_daily_content_plans', methods: ['POST'])]
    public function __invoke(KernelInterface $kernel, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $chatId = $data['message']['chat']['id'] ?? null;
        $chatText = $data['message']['text'] ?? null;
        $allowedCharIds = $kernel->getContainer()->getParameter('telegram_chat_id');

        if ($chatText !== '/report' || (!in_array($chatId, $allowedCharIds))) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:send-daily-content-plans',
            '--chatId' => (string) $chatId,
        ]);
        $output = new NullOutput();

        $application->run($input, $output);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
