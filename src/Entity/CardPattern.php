<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Component\CardPattern\Dtos\CardPatternCreateFromCardDto;
use App\Controller\CardPatternCreateFromCardAction;
use App\Entity\Interfaces\UpdatedAtSettableInterface;
use App\Repository\CardPatternRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CardPatternRepository::class)]
#[ApiResource(
    operations: [
        new Get(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_SMM')"),
        new GetCollection(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_SMM')"),
        new Post(
            uriTemplate: '/card-patterns/from-card',
            controller: CardPatternCreateFromCardAction::class,
            denormalizationContext: ['groups' => ['card-pattern:create-from-card:write']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_SMM')",
            input: CardPatternCreateFromCardDto::class,
            read: false,
        ),
        new Patch(
            denormalizationContext: ['groups' => ['card-pattern:write']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_SMM')",
        ),
        new Delete(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_SMM')"),
    ],
    normalizationContext: ['groups' => ['card-pattern:read']],
    denormalizationContext: ['groups' => ['card-pattern:write']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'updatedAt', 'deadline'])]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial', 'createdBy.id' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['deadline'])]
class CardPattern implements UpdatedAtSettableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['card-pattern:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    #[Groups(['card-pattern:read'])]
    private ?User $createdBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['card-pattern:read'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    #[Groups(['card-pattern:read', 'card-pattern:write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['card-pattern:read', 'card-pattern:write'])]
    private ?\DateTime $deadline = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['card-pattern:read', 'card-pattern:write'])]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDeadline(): ?\DateTime
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTime $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
