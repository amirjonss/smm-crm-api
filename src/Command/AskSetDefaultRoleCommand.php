<?php

namespace App\Command;

use App\Component\User\UserManager;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ask:roles:set-default',
    description: 'Sets ROLE_SMM for users with empty roles',
)]
class AskSetDefaultRoleCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private UserManager $userManager,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $users = $this->userRepository->findAll();
        $updatedCount = 0;

        foreach ($users as $user) {
            if (empty($user->getRoles()) || $user->getRoles() === ['ROLE_USER']) {
                $user->setRoles(['ROLE_SMM']);
                $this->userManager->save($user, true);
                $updatedCount++;
                $io->text(sprintf('Set ROLE_SMM for user #%d (%s)', $user->getId(), $user->getEmail()));
            }
        }

        if ($updatedCount === 0) {
            $io->info('No users with empty roles found.');
        } else {
            $io->success(sprintf('Updated %d user(s) with ROLE_SMM.', $updatedCount));
        }

        return Command::SUCCESS;
    }
}
