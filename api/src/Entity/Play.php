<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\PlayRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PlayRepository::class)]
#[ApiResource(
    description: "A single play of a game between players.  Aka: Match, Round… (reserved words)"
)]
#[GetCollection]
#[Post]
#[Get]
//#[Put(security: "is_granted('ROLE_ADMIN') or object.is_granted_write(user)")]
#[Patch(security: "is_granted('ROLE_ADMIN') or object.is_granted_write(user)")]
#[Delete(security: "is_granted('ROLE_ADMIN') or object.is_granted_write(user)")]
class Play
{

    /**
     * @var Uuid A Universally unique identifier for this player.
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ApiProperty(writable: false)]
    private Uuid $id;

    #[ORM\Column]
    #[ApiProperty(writable: false)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[ApiProperty(description: "A json pickle of the whole game state.")]
    private ?string $json = null;

    #[ORM\ManyToMany(targetEntity: Player::class, inversedBy: 'plays')]
    private Collection $players;

    public function is_granted_write(Player $user): bool
    {
        return in_array($user, (array) $this->getPlayers());
    }

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->players = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getJson(): ?string
    {
        return $this->json;
    }

    public function setJson(?string $json): self
    {
        $this->json = $json;

        return $this;
    }

    /**
     * @return Collection<Uuid, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        $this->players->removeElement($player);

        return $this;
    }

}
