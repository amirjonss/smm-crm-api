<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\ReportType;
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
    #[Route('/api/webhook/content-plans/report', name: 'webhook_content_plans_report', methods: ['POST'])]
    public function __invoke(KernelInterface $kernel, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $chatId = $data['message']['chat']['id'] ?? null;
        $chatText = $data['message']['text'] ?? null;
        $allowedChatIds = $kernel->getContainer()->getParameter('telegram_chat_id');

        if (!in_array($chatId, $allowedChatIds)) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        $reportType = match ($chatText) {
            '/report' => ReportType::TODAY,
            '/report_yesterday' => ReportType::YESTERDAY,
            default => null,
        };

        if ($reportType === null) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:send-content-plans-report',
            '--chatId' => (string) $chatId,
            '--reportType' => $reportType->value,
        ]);
        $output = new NullOutput();

        $application->run($input, $output);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
