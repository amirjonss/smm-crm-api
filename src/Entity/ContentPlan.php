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
use App\Controller\DeleteAction;
use App\Entity\Interfaces\CreatedAtSettableInterface;
use App\Entity\Interfaces\CreatedBySettableInterface;
use App\Entity\Interfaces\DeletedBySettableInterface;
use App\Entity\Interfaces\UpdatedAtSettableInterface;
use App\Entity\Interfaces\UpdatedBySettableInterface;
use App\Entity\Traits\CreatedUpdatedDeletedAtAndByTrait;
use App\Repository\ContentPlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContentPlanRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Delete(
            controller: DeleteAction::class,
            security: 'object.getProject().getExecutor() == user'
        ),
        new Post(security: 'is_granted("ROLE_SMM")'),
        new Patch(
            security: 'object.getProject().getExecutor() == user'
        ),
    ],
    normalizationContext: ['groups' => ['content-plan:read']],
    denormalizationContext: ['groups' => ['content-plan:write']],
    paginationClientItemsPerPage: true,
)]
#[ApiFilter(SearchFilter::class, properties: ['project.id' => 'exact', 'date' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['date'])]
#[ApiFilter(OrderFilter::class, properties: ['position', 'date', 'id'])]
class ContentPlan implements CreatedAtSettableInterface, CreatedBySettableInterface, UpdatedAtSettableInterface, UpdatedBySettableInterface, DeletedBySettableInterface
{
    use CreatedUpdatedDeletedAtAndByTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['content-plan:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['content-plan:read', 'content-plan:write'])]
    #[Assert\NotBlank]
    private ?string $post = null;

    #[ORM\Column(length: 255)]
    #[Groups(['content-plan:read', 'content-plan:write'])]
    #[Assert\NotBlank]
    #[Assert\Choice(['Reels', 'Carousel', 'Post', 'Animation', 'Story'], max: 1)]
    private ?string $format = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['content-plan:read', 'content-plan:write'])]
    #[Assert\NotBlank]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['content-plan:read', 'content-plan:write'])]
    private ?string $idea = null;

    #[ORM\ManyToOne(inversedBy: 'contentPlans')]
    #[Groups(['content-plan:read', 'content-plan:write'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Project $project = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    #[Groups(['content-plan:read'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['content-plan:read'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['content-plan:read'])]
    private ?\DateTimeInterface $deletedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['content-plan:read'])]
    private ?UserInterface $createdBy = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['content-plan:read'])]
    private ?UserInterface $updatedBy = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['content-plan:read'])]
    private ?UserInterface $deletedBy = null;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    #[Groups(['content-plan:read', 'content-plan:write'])]
    private int $position = 0;

    #[ORM\ManyToMany(targetEntity: ContentPlanPlatform::class, inversedBy: 'contentPlans', cascade: [
        'persist',
        'remove',
    ])]
    #[Groups(['content-plan:read', 'content-plan:write'])]
    private Collection $platforms;

    public function __construct()
    {
        $this->platforms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPost(): ?string
    {
        return $this->post;
    }

    public function setPost(string $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getIdea(): ?string
    {
        return $this->idea;
    }

    public function setIdea(?string $idea): static
    {
        $this->idea = $idea;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

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

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedBy(): ?UserInterface
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?UserInterface $createdBy): static
    {
        $this->createdBy = $createdBy;

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

    public function getDeletedBy(): ?UserInterface
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?UserInterface $deletedBy): static
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    /**
     * @return Collection<int, ContentPlanPlatform>
     */
    public function getPlatforms(): Collection
    {
        return $this->platforms;
    }

    public function addPlatform(ContentPlanPlatform $platform): static
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms->add($platform);
            $platform->addContentPlan($this);
        }

        return $this;
    }

    public function removePlatform(ContentPlanPlatform $platform): static
    {
        if ($this->platforms->removeElement($platform)) {
            $platform->removeContentPlan($this);
        }

        return $this;
    }
}
