<?php

namespace App\Entity;

use App\Repository\MatchStatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchStatRepository::class)]
class MatchStat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $minutesPlayed = null;

    #[ORM\Column(nullable: true)]
    private ?int $goals = null;

    #[ORM\Column(nullable: true)]
    private ?int $assists = null;

    #[ORM\Column(nullable: true)]
    private ?int $yellowCards = null;

    #[ORM\Column(nullable: true)]
    private ?int $whiteCards = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $redCard = null;

    #[ORM\Column(nullable: true)]
    private ?int $coachRating = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participation $participation = null;

    #[ORM\ManyToOne]
    private ?Position $position = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMinutesPlayed(): ?int
    {
        return $this->minutesPlayed;
    }

    public function setMinutesPlayed(?int $minutesPlayed): static
    {
        $this->minutesPlayed = $minutesPlayed;

        return $this;
    }

    public function getGoals(): ?int
    {
        return $this->goals;
    }

    public function setGoals(?int $goals): static
    {
        $this->goals = $goals;

        return $this;
    }

    public function getAssists(): ?int
    {
        return $this->assists;
    }

    public function setAssists(?int $assists): static
    {
        $this->assists = $assists;

        return $this;
    }

    public function getYellowCards(): ?int
    {
        return $this->yellowCards;
    }

    public function setYellowCards(?int $yellowCards): static
    {
        $this->yellowCards = $yellowCards;

        return $this;
    }

    public function getWhiteCards(): ?int
    {
        return $this->whiteCards;
    }

    public function setWhiteCards(?int $whiteCards): static
    {
        $this->whiteCards = $whiteCards;

        return $this;
    }

    public function getRedCard(): ?string
    {
        return $this->redCard;
    }

    public function setRedCard(?string $redCard): static
    {
        $this->redCard = $redCard;

        return $this;
    }

    public function getCoachRating(): ?int
    {
        return $this->coachRating;
    }

    public function setCoachRating(?int $coachRating): static
    {
        $this->coachRating = $coachRating;

        return $this;
    }

    public function getParticipation(): ?Participation
    {
        return $this->participation;
    }

    public function setParticipation(Participation $participation): static
    {
        $this->participation = $participation;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): static
    {
        $this->position = $position;

        return $this;
    }
}
