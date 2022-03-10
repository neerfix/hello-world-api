<?php

namespace App\Entity;

use App\Entity\Interfaces\Statuable;
use App\Entity\Traits\StatuableTrait;
use App\Repository\StepRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="step", indexes={
 *     @ORM\Index(name="status_idx", columns={ "status" })
 * })
 * @ORM\Entity(repositoryClass=StepRepository::class)
 */
class Step implements Statuable
{
    use StatuableTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer", unique=true)
     */
    private int $id;

    /**
     * @ORM\Column(name="uuid", type="string", length="180", unique=true)
     * @Groups("step.by.current")
     */
    private string $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="Travel", inversedBy="steps")
     * @ORM\JoinColumn(name="travel_id", referencedColumnName="id", nullable="false")
     */
    private Travel $travelId;

    /**
     * @ORM\ManyToMany(targetEntity="Album", inversedBy="steps")
     * @ORM\JoinTable(name="step_albums",
     * joinColumns={@ORM\JoinColumn(name="step_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="album_id",referencedColumnName="id")})
     */
    private Collection $albumId;

    /**
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="steps")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     */
    private Place $placeId;

    /**
     * @ORM\Column(name="started_at", type="date", nullable="true")
     * @Groups("step.by.current")
     */
    private ?DateTime $startedAt = null;

    /**
     * @ORM\Column(name="ended_at", type="date", nullable="true")
     * @Groups("step.by.current")
     */
    private ?DateTime $endedAt = null;

    /**
     * @ORM\Column(name="created_at", type="date")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="date")
     */
    private DateTime $updatedAt;

    public function __construct()
    {
        $this->albumId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): Step
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getTravelId(): ?Travel
    {
        return $this->travelId;
    }

    public function setTravelId(?Travel $travelId): Step
    {
        $this->travelId = $travelId;

        return $this;
    }

    /**
     * @return Collection|Album[]
     */
    public function getAlbumId(): Collection
    {
        return $this->albumId;
    }

    public function addAlbumId(Album $albumId): Step
    {
        if (!$this->albumId->contains($albumId)) {
            array_push($this->albumId, $albumId);
        }

        return $this;
    }

    public function removeAlbumId(Album $albumId): Step
    {
        $this->albumId->removeElement($albumId);

        return $this;
    }

    public function getPlaceId(): ?Place
    {
        return $this->placeId;
    }

    public function setPlaceId(?Place $placeId): Step
    {
        $this->placeId = $placeId;

        return $this;
    }

    public function getStartedAt(): ?DateTime
    {
        return $this->startedAt;
    }

    public function setStartedAt(?DateTime $startedAt = null): Step
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?DateTime
    {
        return $this->endedAt;
    }

    public function setEndedAt(?DateTime $endedAt = null): Step
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Step
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): Step
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
