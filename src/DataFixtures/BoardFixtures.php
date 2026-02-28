<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Board;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BoardFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct()
    {
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $admin = $this->getReference('user-admin', User::class);
        $board1 = new Board();
        $board1->setName('Test Board 1');
        $board1->setPosition(0);
        $board1->setCreatedAt(new \DateTime());
        $board1->setCreatedBy($admin);
        $this->addReference('board-1', $board1);

        $manager->persist($board1);

        $board2 = new Board();
        $board2->setName('Test Board 2');
        $board2->setPosition(1);
        $board2->setCreatedAt(new \DateTime());
        $board2->setCreatedBy($admin);
        $this->addReference('board-2', $board2);

        $manager->persist($board2);
        $manager->flush();
    }
}
