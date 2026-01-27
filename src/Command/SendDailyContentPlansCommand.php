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
        $dayOfWeek = $this->getDayOfWeekInUzbek((int)date('w'));

        if (empty($plans)) {
            $io->info('No content plans for today.');
            $this->sendMessage($this->telegramBotToken, $this->telegramChatId, "<b>{$dayOfWeek} - {$date}</b>\n\nНа сегодня планов нет.");
            return Command::SUCCESS;
        }

        $message = "<b>{$dayOfWeek} - {$date}</b>\n\n";

        // Group plans by project
        $projectPlans = [];
        foreach ($plans as $plan) {
            $project = $plan->getProject();
            $projectName = $project ? $project->getName() : 'Неизвестный проект';

            if (!isset($projectPlans[$projectName])) {
                $projectPlans[$projectName] = [];
            }

            foreach ($plan->getPlatforms() as $platform) {
                $pNameRaw = $platform->getName();
                $pStatusRaw = $platform->getStatus();
                $projectPlans[$projectName][$pNameRaw] = $pStatusRaw;
            }
        }

        foreach ($projectPlans as $projectName => $platforms) {
            $message .= "<b>" . htmlspecialchars($projectName) . "</b>\n";

            $platformParts = [];
            $platformOrder = ['INSTAGRAM', 'TELEGRAM', 'FACEBOOK', 'YOUTUBE'];

            foreach ($platformOrder as $platformKey) {
                $shortName = match ($platformKey) {
                    'INSTAGRAM' => 'IG',
                    'TELEGRAM' => 'TG',
                    'FACEBOOK' => 'FB',
                    'YOUTUBE' => 'YouTube',
                    default => $platformKey,
                };

                if (isset($platforms[$platformKey])) {
                    $statusEmoji = match ($platforms[$platformKey]) {
                        'PUBLISHED' => ' ✅',
                        'CANCELED' => ' ❌',
                        'NOT_PUBLISHED' => ' 🔴',
                        'RESCHEDULED' => ' 🔄',
                        default => '',
                    };
                    $platformParts[] = $shortName . $statusEmoji;
                } else {
                    $platformParts[] = $shortName;
                }
            }

            $message .= implode(' | ', $platformParts) . "\n\n";
        }

        $message = rtrim($message);

        try {
            $this->sendMessage($this->telegramBotToken, $this->telegramChatId, $message);
            $io->success('Message sent to Telegram.');
        } catch (RuntimeException $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function getDayOfWeekInUzbek(int $dayNumber): string
    {
        return match ($dayNumber) {
            0 => 'YAKSHANBA',
            1 => 'DUSHANBA',
            2 => 'SESHANBA',
            3 => 'CHORSHANBA',
            4 => 'PAYSHANBA',
            5 => 'JUMA',
            6 => 'SHANBA',
            default => '',
        };
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
