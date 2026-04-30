<?php

namespace App\Entity;

use App\Repository\FeedbackTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: FeedbackTypeRepository::class)]
class FeedbackType
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $nature = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $severity = null;

    #[ORM\Column(length: 3)]
    private ?string $isDefault = null;

    #[ORM\Column(length: 15)]
    private ?string $status = null;

    #[ORM\ManyToOne]
    private ?Club $club = null;

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

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(string $nature): static
    {
        $this->nature = $nature;

        return $this;
    }

    public function getSeverity(): ?string
    {
        return $this->severity;
    }

    public function setSeverity(?string $severity): static
    {
        $this->severity = $severity;

        return $this;
    }

    public function getIsDefault(): ?string
    {
        return $this->isDefault;
    }

    public function setIsDefault(string $isDefault): static
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): static
    {
        $this->club = $club;

        return $this;
    }
}
