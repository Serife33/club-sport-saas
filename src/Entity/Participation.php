<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $status = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $actualPresence = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $absenceReason = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $documentUrl = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $documentType = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $declaredAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $notifiedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Occurrence $occurrence = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?User $declaredBy = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getActualPresence(): ?string
    {
        return $this->actualPresence;
    }

    public function setActualPresence(?string $actualPresence): static
    {
        $this->actualPresence = $actualPresence;

        return $this;
    }

    public function getAbsenceReason(): ?string
    {
        return $this->absenceReason;
    }

    public function setAbsenceReason(?string $absenceReason): static
    {
        $this->absenceReason = $absenceReason;

        return $this;
    }

    public function getDocumentUrl(): ?string
    {
        return $this->documentUrl;
    }

    public function setDocumentUrl(?string $documentUrl): static
    {
        $this->documentUrl = $documentUrl;

        return $this;
    }

    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    public function setDocumentType(?string $documentType): static
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function getDateTime(): ?\DateTime
    {
        return $this->declaredAt;
    }

    public function setDateTime(?\DateTime $dateTime): static
    {
        $this->declaredAt = $dateTime;

        return $this;
    }

    public function getNotifiedAt(): ?\DateTime
    {
        return $this->notifiedAt;
    }

    public function setNotifiedAt(?\DateTime $notifiedAt): static
    {
        $this->notifiedAt = $notifiedAt;

        return $this;
    }

    public function getOccurrence(): ?Occurrence
    {
        return $this->occurrence;
    }

    public function setOccurrence(?Occurrence $occurrence): static
    {
        $this->occurrence = $occurrence;

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

    public function getDeclaredBy(): ?User
    {
        return $this->declaredBy;
    }

    public function setDeclaredBy(?User $declaredBy): static
    {
        $this->declaredBy = $declaredBy;

        return $this;
    }
}
