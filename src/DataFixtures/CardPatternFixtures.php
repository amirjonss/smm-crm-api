<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\CardPattern;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CardPatternFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CardFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser = $this->getReference('user-admin', User::class);

        $cardPattern1 = new CardPattern();
        $cardPattern1->setCreatedBy($adminUser);
        $cardPattern1->setName('Test Card Pattern 1');
        $cardPattern1->setDescription('Fixture card pattern description 1');
        $cardPattern1->setDeadline(new \DateTime('+2 days'));
        $cardPattern1->setUpdatedAt(new \DateTime());
        $this->addReference('card-pattern-1', $cardPattern1);
        $manager->persist($cardPattern1);

        $cardPattern2 = new CardPattern();
        $cardPattern2->setCreatedBy($adminUser);
        $cardPattern2->setName('Test Card Pattern 2');
        $cardPattern2->setDescription('Fixture card pattern description 2');
        $cardPattern2->setDeadline(new \DateTime('+5 days'));
        $cardPattern2->setUpdatedAt(new \DateTime());
        $this->addReference('card-pattern-2', $cardPattern2);
        $manager->persist($cardPattern2);

        $cardPattern3 = new CardPattern();
        $cardPattern3->setCreatedBy($adminUser);
        $cardPattern3->setName('Test Card Pattern 3');
        $cardPattern3->setDescription('Fixture card pattern description 3');
        $cardPattern3->setDeadline(null);
        $cardPattern3->setUpdatedAt(new \DateTime());
        $this->addReference('card-pattern-3', $cardPattern3);
        $manager->persist($cardPattern3);

        $manager->flush();
    }
}
