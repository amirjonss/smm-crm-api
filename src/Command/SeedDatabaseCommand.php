<?php

namespace App\Command;

use App\Entity\ContentPlan;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:seed-database',
    description: 'Seeds the database with test data',
)]
class SeedDatabaseCommand extends Command
{
    private const FIRST_NAMES = ['James', 'Mary', 'Robert', 'Patricia', 'John', 'Jennifer', 'Michael', 'Linda', 'David', 'Elizabeth', 'William', 'Barbara', 'Richard', 'Susan', 'Joseph', 'Jessica', 'Thomas', 'Sarah', 'Charles', 'Karen', 'Christopher', 'Nancy', 'Matthew', 'Lisa', 'Anthony', 'Betty', 'Mark', 'Margaret', 'Donald', 'Sandra', 'Steven', 'Ashley', 'Paul', 'Kimberly', 'Andrew', 'Emily', 'Joshua', 'Donna', 'Kenneth', 'Michelle'];
    private const LAST_NAMES = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin', 'Lee', 'Perez', 'Thompson', 'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson', 'Walker', 'Young', 'Allen', 'King', 'Wright', 'Scott', 'Torres', 'Nguyen', 'Hill', 'Flores'];
    
    private const PROJECT_ADJECTIVES = ['Alpha', 'Beta', 'Gamma', 'Delta', 'NextGen', 'Global', 'Strategic', 'Creative', 'Digital', 'Prime', 'Elite', 'Vanguard', 'Pioneer', 'Apex', 'Zenith', 'Summit', 'Vertex', 'Pinnacle', 'Acme', 'Meridian', 'Quantum', 'Horizon', 'Flux', 'Solar', 'Lunar', 'Nova', 'Nebula', 'Echo', 'Swift', 'Bright'];
    private const PROJECT_NOUNS = ['Campaign', 'Launch', 'Rebrand', 'Strategy', 'Initiative', 'Project', 'Program', 'Operation', 'Mission', 'Quest', 'Venture', 'Undertaking', 'Enterprise', 'Pursuit', 'Drive', 'Push', 'Movement', 'Crusade', 'Blitz', 'Slogan', 'Hub', 'System', 'Platform', 'Network', 'Portal', 'Engine', 'Core', 'Base', 'Flow', 'Pulse'];
    private const PROJECT_SUFFIXES = ['v1', 'v2', '2026', 'Internal', 'External', 'Phase 1', 'Phase 2', 'Main', 'Support', 'Direct'];

    private const POST_TEMPLATES = [
        '5 Tips for {topic}',
        'Why you need {topic}',
        'The future of {topic}',
        'How to master {topic}',
        'Behind the scenes: {topic}',
        'Our take on {topic}',
        'The truth about {topic}',
        'Top 10 {topic} hacks',
        'Beginner\'s guide to {topic}',
        '{topic} explained in 60 seconds',
        'Customer success story: {topic}',
        'Meet the team: {topic} experts',
        'We are hiring: {topic} specialists',
        'Event recap: {topic} summit',
        'New feature alert: {topic} integration',
        'Comparing {topic} vs. {topic2}',
        'Stop doing this in {topic}',
        'The secret to {topic} success',
        'Level up your {topic} game',
        'Is {topic} dead?'
    ];
    private const TOPICS = ['Marketing', 'SEO', 'Design', 'Development', 'Sales', 'Branding', 'Social Media', 'Content', 'Analytics', 'Strategy', 'Innovation', 'Tech', 'AI', 'Automation', 'Growth', 'Management', 'Leadership', 'Productivity', 'Remote Work', 'Startup Life', 'UX', 'Cloud', 'Data', 'Security', 'Mobile', 'Web', 'Ecommerce', 'UI', 'Backend', 'Frontend'];
    
    private const IDEAS = [
        'Showcase a day in the life of an employee',
        'Interview a satisfied customer',
        'Share a quick tutorial video',
        'Post a poll to engage followers',
        'Share industry news and insights',
        'Highlight a company milestone',
        'Introduce a new team member',
        'Share a funny office moment',
        'Run a contest or giveaway',
        'Answer frequently asked questions',
        'Tease an upcoming product launch',
        'Share a motivational quote',
        'Post a "Throwback Thursday" photo',
        'Share a user-generated content',
        'Go live for a Q&A session',
        'Share a checklist or infographic',
        'Promote a blog post or case study',
        'Celebrate a holiday or special event',
        'Share a behind-the-scenes look',
        'Ask for feedback on a new idea',
        'Create a "meme" relevant to our niche',
        'Debunk a common industry myth',
        'Share a preview of a new project',
        'Post a client testimonial',
        'Share a helpful resource or tool'
    ];

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Seeding Database');

        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        $io->section('Truncating tables...');
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        $connection->executeStatement('TRUNCATE TABLE content_plan');
        $connection->executeStatement('TRUNCATE TABLE project');
        $connection->executeStatement('TRUNCATE TABLE user');
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
        $io->success('Tables truncated.');

        $io->section('Generating data...');

        $userCount = 10;
        $projectCountPerUser = 5;

        $progressBar = new ProgressBar($output, $userCount * $projectCountPerUser);
        $progressBar->start();

        $formats = ['Reels', 'Carousel', 'Post', 'Animation', 'Story'];
        $statuses = ['PUBLISHED', 'CANCELED', 'NOT_PUBLISHED', 'RESCHEDULED'];

        for ($i = 1; $i <= $userCount; $i++) {
            $firstName = self::FIRST_NAMES[array_rand(self::FIRST_NAMES)];
            $lastName = self::LAST_NAMES[array_rand(self::LAST_NAMES)];

            $user = new User();
            $user->setEmail(strtolower($firstName . '.' . $lastName . $i . '@example.com'));
            $user->setGivenName($firstName);
            $user->setFamilyName($lastName);
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt(new \DateTime());
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

            $this->entityManager->persist($user);

            for ($j = 1; $j <= $projectCountPerUser; $j++) {
                $adj = self::PROJECT_ADJECTIVES[array_rand(self::PROJECT_ADJECTIVES)];
                $noun = self::PROJECT_NOUNS[array_rand(self::PROJECT_NOUNS)];
                $suffix = self::PROJECT_SUFFIXES[array_rand(self::PROJECT_SUFFIXES)];
                $projectName = "$adj $noun ($suffix) - " . rand(100, 999);

                $project = new Project();
                $project->setName($projectName);
                $project->setExecutor($user);
                $project->setCreatedBy($user);
                $project->setCreatedAt(new \DateTime());
                $project->setPhone($this->generatePhone());
                
                $this->entityManager->persist($project);

                $this->createContentPlans($project, $user, 4, 0, $formats, $statuses);   // This Month
                $this->createContentPlans($project, $user, 3, -1, $formats, $statuses);  // Last Month
                $this->createContentPlans($project, $user, 3, -2, $formats, $statuses);  // Prev Month
                
                $progressBar->advance();
            }
            
            $this->entityManager->flush();
            $this->entityManager->clear();
        }

        $progressBar->finish();
        $io->newLine(2);
        $io->success('Database seeded successfully!');

        return Command::SUCCESS;
    }

    private function generatePhone(): string
    {
        $code = rand(10, 99);
        $part1 = rand(100, 999);
        $part2 = rand(10, 99);
        $part3 = rand(10, 99);
        return sprintf("998 (%d) %d - %d - %d", $code, $part1, $part2, $part3);
    }

    private function createContentPlans(Project $project, User $user, int $count, int $monthOffset, array $formats, array $statuses): void
    {
        for ($k = 0; $k < $count; $k++) {
            $template = self::POST_TEMPLATES[array_rand(self::POST_TEMPLATES)];
            $topic = self::TOPICS[array_rand(self::TOPICS)];
            $topic2 = self::TOPICS[array_rand(self::TOPICS)];
            
            $postTitle = str_replace(['{topic}', '{topic2}'], [$topic, $topic2], $template);
            $postTitle .= " #" . rand(100, 999);

            $plan = new ContentPlan();
            $plan->setPost($postTitle);
            $plan->setFormat($formats[array_rand($formats)]);
            $plan->setStatus($statuses[array_rand($statuses)]);
            $plan->setIdea(self::IDEAS[array_rand(self::IDEAS)] . " (Ref " . rand(1, 100) . ")");
            $plan->setProject($project);
            $plan->setCreatedBy($user);
            $plan->setCreatedAt(new \DateTime());
            
            $date = new \DateTime();
            if ($monthOffset !== 0) {
                $date->modify((string)$monthOffset . ' month');
            }
            $daysInMonth = (int)$date->format('t');
            $randomDay = rand(1, $daysInMonth);
            $date->setDate((int)$date->format('Y'), (int)$date->format('m'), $randomDay);
            $date->setTime(rand(0, 23), rand(0, 59));
            
            $plan->setDate($date);
            $plan->setPosition($k);

            $this->entityManager->persist($plan);
        }
    }
}
