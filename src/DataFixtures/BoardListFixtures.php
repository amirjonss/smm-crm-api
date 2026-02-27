<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Board;
use App\Entity\BoardList;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BoardListFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BoardFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $board1 = $this->getReference('board-1', Board::class);
        $board2 = $this->getReference('board-2', Board::class);
        $smmUser = $this->getReference('user-smm', User::class);

        $boardList1 = new BoardList();
        $boardList1->setName('Test Board List 1');
        $boardList1->setPosition(0);
        $boardList1->setBoard($board1);
        $boardList1->setCreatedAt(new \DateTime());
        $boardList1->setCreatedBy($smmUser);
        $this->addReference('board-list-1', $boardList1);

        $manager->persist($boardList1);

        $boardList2 = new BoardList();
        $boardList2->setName('Test Board List 2');
        $boardList2->setPosition(0);
        $boardList2->setBoard($board2);
        $boardList2->setCreatedAt(new \DateTime());
        $boardList2->setCreatedBy($smmUser);
        $this->addReference('board-list-2', $boardList2);

        $manager->persist($boardList2);
        $manager->flush();
    }
}
