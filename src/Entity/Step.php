<?php

namespace App\Entity;

use App\Repository\StepRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StepRepository::class)]
class Step
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: travel::class, inversedBy: 'steps')]
    #[ORM\JoinColumn(nullable: false)]
    private $travel_id;

    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'steps')]
    private $album_id;

    #[ORM\ManyToOne(targetEntity: Place::class, inversedBy: 'steps')]
    #[ORM\JoinColumn(nullable: false)]
    private $placeÃ_id;

    #[ORM\Column(type: 'datetime_immutable')]
    private $started_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private $ended_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updated_at;

    public function __construct()
    {
        $this->album_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTravelId(): ?travel
    {
        return $this->travel_id;
    }

    public function setTravelId(?travel $travel_id): self
    {
        $this->travel_id = $travel_id;

        return $this;
    }

    /**
     * @return Collection|Album[]
     */
    public function getAlbumId(): Collection
    {
        return $this->album_id;
    }

    public function addAlbumId(Album $albumId): self
    {
        if (!$this->album_id->contains($albumId)) {
            $this->album_id[] = $albumId;
        }

        return $this;
    }

    public function removeAlbumId(Album $albumId): self
    {
        $this->album_id->removeElement($albumId);

        return $this;
    }

    public function getPlaceÃId(): ?Place
    {
        return $this->placeÃ_id;
    }

    public function setPlaceÃId(?Place $placeÃ_id): self
    {
        $this->placeÃ_id = $placeÃ_id;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->started_at;
    }

    public function setStartedAt(\DateTimeImmutable $started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->ended_at;
    }

    public function setEndedAt(\DateTimeImmutable $ended_at): self
    {
        $this->ended_at = $ended_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
