<?php

namespace App\Component\CardLog;

use App\Component\Core\AbstractManager;
use Doctrine\ORM\EntityManagerInterface;

class CardLogManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }
}
