<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Component\User\Enum\Roles;
use App\Component\User\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function __construct(private UserFactory $userFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser = $this->userFactory->create('admin@example.com', 'passwd', 'admin', 'adminov', [Roles::ADMIN->value]
        );
        $manager->persist($adminUser);
        $this->addReference('user-admin', $adminUser);

        $smmUser = $this->userFactory->create('smm@example.com', 'passwd', 'smm', 'smmov', [Roles::SMM->value]);
        $manager->persist($smmUser);
        $this->addReference('user-smm', $smmUser);

        $smmUser2 = $this->userFactory->create('smm2@example.com', 'passwd', 'smm', 'smmov', [Roles::SMM->value]);
        $manager->persist($smmUser2);
        $this->addReference('user-smm-2', $smmUser2);

        $operatorUser = $this->userFactory->create(
            'operator@example.com',
            'passwd',
            'operator',
            'operatorov',
            [Roles::OPERATOR->value]
        );
        $manager->persist($operatorUser);

        $manager->flush();
        $this->addReference('user-operator', $operatorUser);
    }
}
