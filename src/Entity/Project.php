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
use App\Controller\DeleteAction;
use App\Entity\Interfaces\CreatedAtSettableInterface;
use App\Entity\Interfaces\CreatedBySettableInterface;
use App\Entity\Interfaces\DeletedBySettableInterface;
use App\Entity\Interfaces\UpdatedAtSettableInterface;
use App\Entity\Interfaces\UpdatedBySettableInterface;
use App\Entity\Traits\CreatedUpdatedDeletedAtAndByTrait;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Delete(
            controller: DeleteAction::class,
            security: "object.getExecutor() == user"
        ),
        new Post(),
        new Patch(security: "object.getExecutor() == user"),
        new Patch(
            uriTemplate: '/projects/{id}/admin',
            denormalizationContext: ['groups' => ['admin:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
    ],
    normalizationContext: ['groups' => ['project:read']],
    denormalizationContext: ['groups' => ['project:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['executor.id' => 'exact'])]
class Project implements
    CreatedAtSettableInterface,
    CreatedBySettableInterface,
    UpdatedAtSettableInterface,
    UpdatedBySettableInterface,
    DeletedBySettableInterface
{
    use CreatedUpdatedDeletedAtAndByTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['project:read', 'content-plan:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['project:read', 'project:write', 'content-plan:read'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['project:read', 'content-plan:read', 'admin:write'])]
    private ?User $executor = null;

    #[ORM\Column(length: 255)]
    #[Groups(['project:read', 'project:write'])]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^998 \(\d{2}\) \d{3} - \d{2} - \d{2}$/',
        message: 'The phone number must be in the format: 998 (90) 707 - 77 - 07',
    )]
    private ?string $phone = null;

    #[ORM\Column]
    #[Groups(['project:read'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['project:read'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['project:read'])]
    private ?User $createdBy = null;

    #[ORM\ManyToOne]
    #[Groups(['project:read'])]
    private ?User $updatedBy = null;

    #[ORM\ManyToOne]
    #[Groups(['project:read'])]
    private ?User $deletedBy = null;

    #[ORM\Column(options: ['default' => true])]
    #[Groups(['project:read', 'admin:write'])]
    private bool $isActive = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['admin:write'])]
    #[Assert\Range(notInRangeMessage: 'Charge day must be between {{ min }} and {{ max }}', min: 1, max: 31)]
    private ?int $chargeDay = null;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(['admin:write'])]
    #[Assert\PositiveOrZero(message: 'Price must be 0 or positive')]
    private int $price = 0;

    /**
     * @var Collection<int, ContentPlan>
     */
    #[ORM\OneToMany(targetEntity: ContentPlan::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $contentPlans;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(['admin:write'])]
    #[Assert\PositiveOrZero(message: 'Price must be 0 or positive')]
    private int $graphicPostCount = 0;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(['admin:write'])]
    #[Assert\PositiveOrZero(message: 'Price must be 0 or positive')]
    private int $videoPostCount = 0;

    public function __construct()
    {
        $this->contentPlans = new ArrayCollection();
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

    public function getExecutor(): ?User
    {
        return $this->executor;
    }

    public function setExecutor(?User $executor): static
    {
        $this->executor = $executor;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

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

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
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

    public function getDeletedBy(): ?User
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?UserInterface $deletedBy): static
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getChargeDay(): ?int
    {
        return $this->chargeDay;
    }

    public function setChargeDay(?int $chargeDay): static
    {
        $this->chargeDay = $chargeDay;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, ContentPlan>
     */
    public function getContentPlans(): Collection
    {
        return $this->contentPlans;
    }

    public function addContentPlan(ContentPlan $contentPlan): static
    {
        if (!$this->contentPlans->contains($contentPlan)) {
            $this->contentPlans->add($contentPlan);
            $contentPlan->setProject($this);
        }

        return $this;
    }

    public function removeContentPlan(ContentPlan $contentPlan): static
    {
        if ($this->contentPlans->removeElement($contentPlan)) {
            // set the owning side to null (unless already changed)
            if ($contentPlan->getProject() === $this) {
                $contentPlan->setProject(null);
            }
        }

        return $this;
    }

    public function getGraphicPostCount(): int
    {
        return $this->graphicPostCount;
    }

    public function setGraphicPostCount(int $graphicPostCount): static
    {
        $this->graphicPostCount = $graphicPostCount;

        return $this;
    }

    public function getVideoPostCount(): int
    {
        return $this->videoPostCount;
    }

    public function setVideoPostCount(int $videoPostCount): static
    {
        $this->videoPostCount = $videoPostCount;

        return $this;
    }
}
