<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
//#[ApiResource(
//    description: "Score of a single player in a single scoreboard."
//)]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $primaryScore = null;

    #[ORM\Column(nullable: true)]
    private ?int $secondaryScore = null;

    #[ORM\Column(nullable: true)]
    private ?int $ternaryScore = null;

    #[ORM\ManyToOne(inversedBy: 'scores')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Scoreboard $scoreboard = null;

    #[ORM\ManyToOne(inversedBy: 'scores')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrimaryScore(): ?int
    {
        return $this->primaryScore;
    }

    public function setPrimaryScore(int $primaryScore): self
    {
        $this->primaryScore = $primaryScore;

        return $this;
    }

    public function getSecondaryScore(): ?int
    {
        return $this->secondaryScore;
    }

    public function setSecondaryScore(?int $secondaryScore): self
    {
        $this->secondaryScore = $secondaryScore;

        return $this;
    }

    public function getTernaryScore(): ?int
    {
        return $this->ternaryScore;
    }

    public function setTernaryScore(?int $ternaryScore): self
    {
        $this->ternaryScore = $ternaryScore;

        return $this;
    }

    public function getScoreboard(): ?Scoreboard
    {
        return $this->scoreboard;
    }

    public function setScoreboard(?Scoreboard $scoreboard): self
    {
        $this->scoreboard = $scoreboard;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }
}
