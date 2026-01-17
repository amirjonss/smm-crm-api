<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\ContentPlanRepository;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:send-daily-content-plans',
    description: 'Sends today\'s content plans to Telegram',
)]
class SendDailyContentPlansCommand extends Command
{
    public function __construct(
        private ContentPlanRepository $contentPlanRepository,
        private string                $telegramBotToken,
        private string                $telegramChatId
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        // No arguments needed, taken from .env
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (empty($this->telegramBotToken) || empty($this->telegramChatId)) {
            $io->error('TELEGRAM_BOT_TOKEN or TELEGRAM_CHAT_ID is not configured in .env');
            return Command::FAILURE;
        }

        $plans = $this->contentPlanRepository->findTodayContentPlans();
        $date = date('d.m.Y');

        if (empty($plans)) {
            $io->info('No content plans for today.');
            $this->sendMessage($this->telegramBotToken, $this->telegramChatId, "📅 <b>Контент-планы на {$date}</b>\n\nНа сегодня планов нет.");
            return Command::SUCCESS;
        }

        $message = "📅 <b>Контент-планы на {$date}</b>\n\n";

        foreach ($plans as $plan) {
            $project = $plan->getProject();
            $projectName = htmlspecialchars($project ? $project->getName() : 'Неизвестный проект');

            $executor = $project ? $project->getExecutor() : null;
            $executorNameRaw = $executor ? trim(($executor->getGivenName() ?? '') . ' ' . ($executor->getFamilyName() ?? '')) : 'Не указан';

            if (empty($executorNameRaw)) {
                $executorNameRaw = 'Не указан';
            }

            $executorName = htmlspecialchars($executorNameRaw);
            $post = htmlspecialchars($plan->getPost());

            $platformsInfo = [];
            foreach ($plan->getPlatforms() as $platform) {
                $pNameRaw = $platform->getName();
                $pName = match ($pNameRaw) {
                    'YOUTUBE' => 'YouTube',
                    'INSTAGRAM' => 'Instagram',
                    'FACEBOOK' => 'Facebook',
                    'TELEGRAM' => 'Telegram',
                    default => htmlspecialchars($pNameRaw ?? 'Unknown'),
                };

                $pStatusRaw = $platform->getStatus();
                $pStatus = match ($pStatusRaw) {
                    'PUBLISHED' => 'Опубликовано ✅',
                    'CANCELED' => 'Отменено ❌',
                    'NOT_PUBLISHED' => 'Не опубликовано ⏳',
                    'RESCHEDULED' => 'Перенесено 🔄',
                    default => htmlspecialchars($pStatusRaw),
                };
                $platformsInfo[] = "  • {$pName}: {$pStatus}";
            }
            $platformsString = !empty($platformsInfo) ? implode("\n", $platformsInfo) : "  • Платформы не указаны";

            $message .= "📌 <b>Проект:</b> {$projectName}\n";
            $message .= "👤 <b>Исполнитель:</b> {$executorName}\n";
            $message .= "📝 <b>Пост:</b> {$post}\n";
            $message .= "📱 <b>Платформы:</b>\n{$platformsString}\n";
            $message .= "--------------------------------\n";
        }

        $message .= "🤖";

        try {
            $this->sendMessage($this->telegramBotToken, $this->telegramChatId, $message);
            $io->success('Message sent to Telegram.');
        } catch (RuntimeException $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function sendMessage(string $token, string $chatId, string $text): void
    {
        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
                'ignore_errors' => true
            ],
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if ($result === false) {
            throw new RuntimeException("Failed to connect to Telegram API.");
        }

        $response = json_decode($result, true);

        if (($response['ok'] ?? false) !== true) {
            throw new RuntimeException("Telegram API Error: " . ($response['description'] ?? 'Unknown error'));
        }
    }
}
