<?php

declare(strict_types=1);

namespace App\Controller\Subscribers;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Board;
use App\Entity\BoardList;
use App\Entity\Card;
use Doctrine\ORM\QueryBuilder;

class ArchivedItemsExtension implements QueryCollectionExtensionInterface
{
    private const ARCHIVABLE_ENTITIES = [
        Card::class,
        BoardList::class,
        Board::class,
    ];

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = [],
    ): void {
        if (!in_array($resourceClass, self::ARCHIVABLE_ENTITIES, true)) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        if ($resourceClass === Board::class) {
            $queryBuilder->leftJoin($rootAlias . '.lists', 'lists', 'WITH', 'lists.isArchived = false');

            return;
        }

        // Dedicated archived endpoints: return only archived items
        if ($operation?->getExtraProperties()['archived_only'] ?? false) {
            $queryBuilder->andWhere(sprintf('%s.isArchived = :archivedFilter', $rootAlias));
            $queryBuilder->setParameter('archivedFilter', true);

            return;
        }

        if ($resourceClass === BoardList::class) {
            $queryBuilder->leftJoin($rootAlias . '.cards', 'cards', 'WITH', 'cards.isArchived = false');
        }

        // Default GetCollection: exclude archived items
        $queryBuilder->andWhere(sprintf('%s.isArchived = :archivedFilter', $rootAlias));
        $queryBuilder->setParameter('archivedFilter', false);
    }
}
