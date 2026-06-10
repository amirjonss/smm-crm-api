<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ContentPlanPlatformRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContentPlanPlatformRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            security: "is_granted('ROLE_ADMIN') or object.getCreatedBy() == user"
        ),
        new GetCollection(),
    ],
    normalizationContext: ['groups' => ['content-plan-platform:read']],
    denormalizationContext: ['groups' => ['content-plan-platform:write']]
)]
class ContentPlanPlatform
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['content-plan-platform:read', 'content-plan:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['content-plan-platform:read', 'content-plan-platform:write', 'content-plan:read', 'content-plan:write'])]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['YOUTUBE', 'INSTAGRAM', 'FACEBOOK', 'TELEGRAM'], max: 1)]
    private ?string $name = null;

    #[ORM\Column(length: 255, options: ['default' => 'NOT_PUBLISHED'])]
    #[Groups(['content-plan-platform:read', 'content-plan-platform:write', 'content-plan:read', 'content-plan:write'])]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['PUBLISHED', 'CANCELED', 'NOT_PUBLISHED', 'RESCHEDULED'])]
    private string $status = 'NOT_PUBLISHED';

    #[ORM\ManyToMany(targetEntity: ContentPlan::class, mappedBy: 'platforms')]
    private Collection $contentPlans;

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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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
            $contentPlan->addPlatform($this);
        }

        return $this;
    }

    public function removeContentPlan(ContentPlan $contentPlan): static
    {
        if ($this->contentPlans->removeElement($contentPlan)) {
            $contentPlan->removePlatform($this);
        }

        return $this;
    }
}
