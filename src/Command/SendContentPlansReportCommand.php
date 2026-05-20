<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\ReportType;
use App\Repository\ContentPlanRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:send-content-plans-report',
    description: 'Sends content plans report to Telegram',
)]
class SendContentPlansReportCommand extends Command
{
    public function __construct(
        private ContentPlanRepository $contentPlanRepository,
        private string $telegramBotToken,
        private array $telegramChatId,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'chatId',
                null,
                InputOption::VALUE_REQUIRED,
                'Telegram chat ID to send the message to'
            )
            ->addOption(
                'reportType',
                null,
                InputOption::VALUE_REQUIRED,
                'Report type: today or yesterday',
                ReportType::TODAY->value
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $chatId = $input->getOption('chatId');

        if (empty($chatId)) {
            $io->error('The --chatId option is required.');

            return Command::FAILURE;
        }

        if (empty($this->telegramBotToken)) {
            $io->error('TELEGRAM_BOT_TOKEN is not configured in .env');

            return Command::FAILURE;
        }

        $reportType = ReportType::tryFrom($input->getOption('reportType'));

        if ($reportType === null) {
            $io->error('Invalid --reportType. Allowed values: today, yesterday.');

            return Command::FAILURE;
        }

        ['from' => $from, 'to' => $to] = $reportType->getDateRange();

        $plans = $this->contentPlanRepository->findContentPlansByDateRange($from, $to);
        $date = $from->format('d.m.Y');
        $dayOfWeek = $this->getDayOfWeekInUzbek((int) $from->format('w'));

        if (empty($plans)) {
            $io->info('No content plans for the selected date.');
            $this->sendMessage($this->telegramBotToken, $chatId, "<b>{$dayOfWeek} - {$date}</b>\n\nНа сегодня планов нет.");

            return Command::SUCCESS;
        }

        $message = "<b>{$dayOfWeek} - {$date}</b>\n\n";

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
            $message .= '<b>' . htmlspecialchars($projectName) . "</b>\n";

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
                }
            }

            $message .= implode(' | ', $platformParts) . "\n\n";
        }

        $message = rtrim($message);

        try {
            $this->sendMessage($this->telegramBotToken, $chatId, $message);
            $io->success('Message sent to Telegram.');
        } catch (\RuntimeException $e) {
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
                'ignore_errors' => true,
            ],
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if ($result === false) {
            throw new \RuntimeException('Failed to connect to Telegram API.');
        }

        $response = json_decode($result, true);

        if (($response['ok'] ?? false) !== true) {
            throw new \RuntimeException('Telegram API Error: ' . ($response['description'] ?? 'Unknown error'));
        }
    }
}
