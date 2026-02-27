<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Component\Card\Enum\CardStatus;
use App\Entity\BoardList;
use App\Entity\Card;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CardFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BoardListFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $boardList1 = $this->getReference('board-list-1', BoardList::class);
        $boardList2 = $this->getReference('board-list-2', BoardList::class);
        $adminUser = $this->getReference('user-admin', User::class);

        $card1 = new Card();
        $card1->setList($boardList1);
        $card1->setName('Test Card 1');
        $card1->setStatus(CardStatus::OPEN);
        $card1->setDescription('Fixture card description 1');
        $card1->setColor('#FF5733');
        $card1->setPosition(1024);
        $card1->setCreatedAt(new \DateTime());
        $card1->setCreatedBy($adminUser);
        $this->addReference('card-1', $card1);
        $manager->persist($card1);

        $card2 = new Card();
        $card2->setList($boardList1);
        $card2->setName('Test Card 2');
        $card2->setStatus(CardStatus::IN_PROGRESS);
        $card2->setDescription('Fixture card description 2');
        $card2->setColor('#33A1FF');
        $card2->setPosition(2048);
        $card2->setCreatedAt(new \DateTime());
        $card2->setCreatedBy($adminUser);
        $this->addReference('card-2', $card2);
        $manager->persist($card2);

        $card3 = new Card();
        $card3->setList($boardList2);
        $card3->setName('Test Card 3');
        $card3->setStatus(CardStatus::DONE);
        $card3->setDescription('Fixture card description 3');
        $card3->setColor('#2ECC71');
        $card3->setPosition(1024);
        $card3->setCreatedAt(new \DateTime());
        $card3->setCreatedBy($adminUser);
        $this->addReference('card-3', $card3);
        $manager->persist($card3);

        $manager->flush();
    }
}
