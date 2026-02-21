<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Component\BoardList\Dtos\BoardListMovePositionDto;
use App\Controller\BoardListCreateAction;
use App\Controller\BoardListMovePositionAction;
use App\Entity\Interfaces\CreatedAtSettableInterface;
use App\Entity\Interfaces\CreatedBySettableInterface;
use App\Entity\Interfaces\UpdatedAtSettableInterface;
use App\Entity\Interfaces\UpdatedBySettableInterface;
use App\Repository\BoardListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BoardListRepository::class)]
#[ORM\Table(name: 'board_list')]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/board_lists/archived',
            paginationItemsPerPage: 20,
            order: ['updatedAt' => 'desc'],
            extraProperties: ['archived_only' => true],
        ),
        new Get(),
        new GetCollection(),
        new Post(
            controller: BoardListCreateAction::class,
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_SMM')",
        ),
        new Post(
            uriTemplate: '/board_lists/move-position',
            controller: BoardListMovePositionAction::class,
            denormalizationContext: ['groups' => ['board-list:move-position:write']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_SMM')",
            input: BoardListMovePositionDto::class,
        ),
        new Patch(
            denormalizationContext: ['groups' => ['board-list:put:write']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_SMM')"
        ),
        new Delete(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_SMM')"),
    ],
    normalizationContext: ['groups' => ['board-list:read']],
    denormalizationContext: ['groups' => ['board-list:write']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'position', 'createdAt'])]
#[ApiFilter(SearchFilter::class, properties: ['board.id' => 'exact', 'name' => 'partial'])]
#[ApiFilter(BooleanFilter::class, properties: ['isArchived'])]
class BoardList implements CreatedAtSettableInterface, CreatedBySettableInterface, UpdatedAtSettableInterface, UpdatedBySettableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['board-list:read', 'board:read', 'card:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Board::class, inversedBy: 'lists')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['board-list:read', 'board-list:write', 'board-list:put:write'])]
    #[Assert\NotNull]
    private ?Board $board = null;

    #[ORM\Column(length: 255)]
    #[Groups(['board-list:read', 'board-list:write', 'board:read', 'board-list:put:write'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['board-list:read', 'board-list:write', 'board:read', 'board-list:put:write'])]
    private bool $isArchived = false;

    #[ORM\Column(length: 32, nullable: true)]
    #[Groups(['board-list:read', 'board-list:write', 'board:read', 'board-list:put:write'])]
    private ?string $color = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(['board-list:read', 'board-list:write', 'board:read'])]
    private ?int $position = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['board-list:read'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['board-list:read'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Groups(['board-list:read'])]
    private ?UserInterface $createdBy = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?UserInterface $updatedBy = null;

    /** @var Collection<int, Card> */
    #[ORM\OneToMany(targetEntity: Card::class, mappedBy: 'list', orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    #[Groups(['board-list:read'])]
    private Collection $cards;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isArchived(): bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): static
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

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

    public function getCreatedBy(): ?UserInterface
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?UserInterface $user): static
    {
        $this->createdBy = $user;

        return $this;
    }

    public function getUpdatedBy(): ?UserInterface
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?UserInterface $user): static
    {
        $this->updatedBy = $user;

        return $this;
    }

    /** @return Collection<int, Card> */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): static
    {
        if (!$this->cards->contains($card)) {
            $this->cards->add($card);
            $card->setList($this);
        }

        return $this;
    }

    public function removeCard(Card $card): static
    {
        if ($this->cards->removeElement($card)) {
            if ($card->getList() === $this) {
                $card->setList(null);
            }
        }

        return $this;
    }
}
