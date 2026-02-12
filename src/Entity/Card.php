<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
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
use App\Component\Board\Enum\CardStatus;
use App\Entity\Interfaces\CreatedAtSettableInterface;
use App\Entity\Interfaces\CreatedBySettableInterface;
use App\Entity\Interfaces\UpdatedAtSettableInterface;
use App\Entity\Interfaces\UpdatedBySettableInterface;
use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CardRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_SMM')"),
        new Patch(),
        new Patch(
            uriTemplate: '/cards/{id}/executors',
            denormalizationContext: ['groups' => ['card-executor:write']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_SMM')",
            name: 'cardExecutors',
        ),
        new Delete(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_SMM')"),
    ],
    normalizationContext: ['groups' => ['card:read']],
    denormalizationContext: ['groups' => ['card:write']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'position', 'createdAt', 'deadline'])]
#[ApiFilter(SearchFilter::class, properties: ['list.id' => 'exact', 'list.board.id' => 'exact', 'name' => 'partial', 'status' => 'exact'])]
#[ApiFilter(BooleanFilter::class, properties: ['isArchived'])]
#[ApiFilter(DateFilter::class, properties: ['deadline'])]
class Card implements
    CreatedAtSettableInterface,
    CreatedBySettableInterface,
    UpdatedAtSettableInterface,
    UpdatedBySettableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['card:read', 'board-list:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: BoardList::class, inversedBy: 'cards')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['card:read', 'card:write'])]
    #[Assert\NotNull]
    private ?BoardList $list = null;

    #[ORM\Column(length: 255)]
    #[Groups(['card:read', 'card:write', 'board-list:read'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 32, enumType: CardStatus::class)]
    #[Groups(['card:read', 'card:write', 'board-list:read'])]
    #[Assert\NotNull]
    private CardStatus $status = CardStatus::OPEN;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['card:read', 'card:write', 'board-list:read'])]
    private bool $isArchived = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['card:read', 'card:write', 'board-list:read'])]
    private ?\DateTime $deadline = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['card:read', 'card:write', 'board-list:read'])]
    #[Assert\NotNull]
    private int $position = 0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['card:read'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['card:read'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Groups(['card:read'])]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?User $updatedBy = null;

    /** @var Collection<int, User> */
    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(name: 'card_executor')]
    #[ORM\JoinColumn(name: 'card_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'executor_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Groups(['card:read', 'card-executor:write'])]
    private Collection $executors;

    /** @var Collection<int, CardLog> */
    #[ORM\OneToMany(targetEntity: CardLog::class, mappedBy: 'card', orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $logs;

    public function __construct()
    {
        $this->executors = new ArrayCollection();
        $this->logs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getList(): ?BoardList
    {
        return $this->list;
    }

    public function setList(?BoardList $list): static
    {
        $this->list = $list;

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

    public function getStatus(): CardStatus
    {
        return $this->status;
    }

    public function setStatus(CardStatus $status): static
    {
        $this->status = $status;

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

    public function getDeadline(): ?\DateTime
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTime $deadline): static
    {
        $this->deadline = $deadline;

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

    /** @return Collection<int, User> */
    public function getExecutors(): Collection
    {
        return $this->executors;
    }

    public function addExecutor(User $executor): static
    {
        if (!$this->executors->contains($executor)) {
            $this->executors->add($executor);
        }

        return $this;
    }

    public function removeExecutor(User $executor): static
    {
        $this->executors->removeElement($executor);

        return $this;
    }

    /** @return Collection<int, CardLog> */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(CardLog $log): static
    {
        if (!$this->logs->contains($log)) {
            $this->logs->add($log);
            $log->setCard($this);
        }

        return $this;
    }

    public function removeLog(CardLog $log): static
    {
        if ($this->logs->removeElement($log)) {
            if ($log->getCard() === $this) {
                $log->setCard(null);
            }
        }

        return $this;
    }
}
