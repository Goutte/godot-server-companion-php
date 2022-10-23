<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16)]
    private ?string $displayName = null;

    #[ORM\OneToOne(inversedBy: 'player', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $authUser = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Score::class, orphanRemoval: true)]
    private Collection $scores;

    #[ORM\ManyToMany(targetEntity: Play::class, mappedBy: 'players')]
    private Collection $plays;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
        $this->plays = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getAuthUser(): ?User
    {
        return $this->authUser;
    }

    public function setAuthUser(User $authUser): self
    {
        $this->authUser = $authUser;

        return $this;
    }

    /**
     * @return Collection<int, Score>
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): self
    {
        if (!$this->scores->contains($score)) {
            $this->scores->add($score);
            $score->setPlayer($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->removeElement($score)) {
            // set the owning side to null (unless already changed)
            if ($score->getPlayer() === $this) {
                $score->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Play>
     */
    public function getPlays(): Collection
    {
        return $this->plays;
    }

    public function addPlay(Play $play): self
    {
        if (!$this->plays->contains($play)) {
            $this->plays->add($play);
            $play->addPlayer($this);
        }

        return $this;
    }

    public function removePlay(Play $play): self
    {
        if ($this->plays->removeElement($play)) {
            $play->removePlayer($this);
        }

        return $this;
    }
}
