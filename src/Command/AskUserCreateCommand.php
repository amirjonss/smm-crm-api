<?php

namespace App\Command;

use App\Component\User\UserFactory;
use App\Component\User\UserManager;
use App\Controller\UserCreateAction;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ask:create-user',
    description: 'This command creates a new user',
)]
class AskUserCreateCommand extends Command
{
    public function __construct(
        private UserCreateAction $createAction,
        private UserFactory $userFactory,
        private UserManager $userManager,
        private UserRepository $userRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $output->writeln([
            '============',
            'User Creator',
            '============',
            '            ',
        ]);
        $helper = new QuestionHelper();

        $emailQuestion = new Question('write you email: ' . PHP_EOL);
        $passwordQuestion = new Question('write your password: ' . PHP_EOL);
        $nameQuestion = new Question('write your name: ' . PHP_EOL);
        $familyNameQuestion = new Question('write your family name: ' . PHP_EOL);

        $email = $helper->ask($input, $output, $emailQuestion);
        $password = $helper->ask($input, $output, $passwordQuestion);
        $name = $helper->ask($input, $output, $nameQuestion);
        $familyName = $helper->ask($input, $output, $familyNameQuestion);

        $this->userFactory->create($email, $password, $name, $familyName);

        $io->success('You have created a new user successfully');

        return Command::SUCCESS;
    }
}
