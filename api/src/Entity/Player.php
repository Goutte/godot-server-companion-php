<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    description: "A beautiful player (like you)"
)]
class Player implements UserInterface, PasswordAuthenticatedUserInterface
{
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column]
//    private ?int $id = null;

    /**
     * @var Uuid A Universally unique identifier for this player.
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ApiProperty(writable: false)]
    private Uuid $id;

    #[ORM\Column]
    #[ApiProperty(writable: false)]
    private array $roles = [];

    /**
     * @var string An immutable username, used for login.
     */
    #[ORM\Column(unique: true)]
    #[ApiProperty]
    private string $username;

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    #[ApiProperty()]
    private ?string $password = null;

    /**
     * @var ?string A display (playa) name, for humans.  May change.
     */
    #[ORM\Column(length: 16)]
    private ?string $displayName = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Score::class, orphanRemoval: true)]
    #[ApiProperty(writable: false)]
    private Collection $scores;

    #[ORM\ManyToMany(targetEntity: Play::class, mappedBy: 'players')]
    #[ApiProperty(writable: false)]
    private Collection $plays;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

//    public function getUuid(): ?Uuid
//    {
//        return $this->uuid;
//    }

//    public function setUuid(Uuid $uuid): self
//    {
//        $this->uuid = $uuid;
//
//        return $this;
//    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
