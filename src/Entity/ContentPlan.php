<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Interfaces\CreatedAtSettableInterface;
use App\Entity\Interfaces\CreatedBySettableInterface;
use App\Entity\Interfaces\DeletedBySettableInterface;
use App\Entity\Interfaces\UpdatedAtSettableInterface;
use App\Entity\Interfaces\UpdatedBySettableInterface;
use App\Entity\Traits\CreatedUpdatedDeletedAtAndByTrait;
use App\Repository\ContentPlanRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContentPlanRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['content-plan:read']],
    denormalizationContext: ['groups' => ['content-plan:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['project.id' => 'exact', 'date' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['date'])]
class ContentPlan implements
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

    #[ORM\Column]
    #[Groups(['content-plan:read'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['content-plan:read'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['content-plan:read'])]
    private ?User $createdBy = null;

    #[ORM\ManyToOne]
    #[Groups(['content-plan:read'])]
    private ?User $updatedBy = null;

    #[ORM\ManyToOne]
    #[Groups(['content-plan:read'])]
    private ?User $deletedBy = null;

    public function getId(): ?int
    {
        return $this->id;
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
}
