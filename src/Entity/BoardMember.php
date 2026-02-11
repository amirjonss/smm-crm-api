<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Component\Board\Enum\BoardMemberRole;
use App\Entity\Interfaces\CreatedAtSettableInterface;
use App\Repository\BoardMemberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BoardMemberRepository::class)]
#[ORM\UniqueConstraint(name: 'board_member_unique', columns: ['board_id', 'user_id'])]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_USER')"),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['board-member:read']],
    denormalizationContext: ['groups' => ['board-member:write']],
)]
#[ApiFilter(SearchFilter::class, properties: ['board.id' => 'exact', 'user.id' => 'exact', 'role' => 'exact'])]
class BoardMember implements CreatedAtSettableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['board-member:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Board::class, inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['board-member:read', 'board-member:write'])]
    #[Assert\NotNull]
    private ?Board $board = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['board-member:read', 'board-member:write'])]
    #[Assert\NotNull]
    private ?User $user = null;

    #[ORM\Column(length: 32, enumType: BoardMemberRole::class)]
    #[Groups(['board-member:read', 'board-member:write'])]
    #[Assert\NotNull]
    //todo add validation assert choice by board role
    private BoardMemberRole $role = BoardMemberRole::VIEWER;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['board-member:read'])]
    private ?\DateTime $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoard(): ?Board
    {
        return $this->board;
    }

    public function setBoard(?Board $board): static
    {
        $this->board = $board;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRole(): BoardMemberRole
    {
        return $this->role;
    }

    public function setRole(BoardMemberRole $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
