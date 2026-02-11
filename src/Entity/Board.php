<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Interfaces\CreatedAtSettableInterface;
use App\Entity\Interfaces\CreatedBySettableInterface;
use App\Entity\Interfaces\UpdatedAtSettableInterface;
use App\Entity\Interfaces\UpdatedBySettableInterface;
use App\Repository\BoardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BoardRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_USER')"),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['board:read']],
    denormalizationContext: ['groups' => ['board:write']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'position', 'createdAt'])]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
class Board implements
    CreatedAtSettableInterface,
    CreatedBySettableInterface,
    UpdatedAtSettableInterface,
    UpdatedBySettableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['board:read', 'board-list:read', 'card:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['board:read', 'board:write', 'board-list:read'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['board:read', 'board:write'])]
    #[Assert\NotNull]
    private int $position = 0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['board:read'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['board:read'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Groups(['board:read'])]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?User $updatedBy = null;

    /** @var Collection<int, BoardList> */
    #[ORM\OneToMany(targetEntity: BoardList::class, mappedBy: 'board', orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    #[Groups(['board:read'])]
    private Collection $lists;

    /** @var Collection<int, BoardMember> */
    #[ORM\OneToMany(targetEntity: BoardMember::class, mappedBy: 'board', orphanRemoval: true)]
    private Collection $members;

    public function __construct()
    {
        $this->lists = new ArrayCollection();
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

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

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?UserInterface $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?UserInterface $updatedBy): static
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /** @return Collection<int, BoardList> */
    public function getLists(): Collection
    {
        return $this->lists;
    }

    public function addList(BoardList $list): static
    {
        if (!$this->lists->contains($list)) {
            $this->lists->add($list);
            $list->setBoard($this);
        }

        return $this;
    }

    public function removeList(BoardList $list): static
    {
        if ($this->lists->removeElement($list)) {
            if ($list->getBoard() === $this) {
                $list->setBoard(null);
            }
        }

        return $this;
    }

    /** @return Collection<int, BoardMember> */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(BoardMember $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
            $member->setBoard($this);
        }

        return $this;
    }

    public function removeMember(BoardMember $member): static
    {
        if ($this->members->removeElement($member)) {
            if ($member->getBoard() === $this) {
                $member->setBoard(null);
            }
        }

        return $this;
    }
}
