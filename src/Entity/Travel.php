<?php

namespace App\Entity;

use App\Repository\TravelRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TravelRepository")
 */
class Travel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer", length="180", unique=true)
     */
    private int $id;

    /**
     * @ORM\Column(name="name", type="string", length="255")
     */
    private string $name;

    /**
     * @ORM\Column(name="budget", type="string", length="255")
     */
    private string $budget;

    /**
     * @ORM\Column(name="is_shared", type="boolean")
     */
    private bool $isShared;

    /**
     * @ORM\Column(name="description", type="text")
     */
    private string $description;

    /**
     * @ORM\Column(name="started_at", type="date")
     */
    private DateTime $startedAt;

    /**
     * @ORM\Column(name="ended_at", type="date")
     */
    private DateTime $endedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="travels")
     * @ORM\Column(name="user_id", nullable="false")
     */
    private User $userId;

    /**
     * @ORM\Column(name="created_at", type="date")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="date")
     */
    private DateTime $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="Album", mappedBy="travel_id")
     */
    private Collection $albums;

    /**
     * @ORM\OneToMany(targetEntity="Step", mappedBy="travel_id")
     */
    private Collection $steps;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
        $this->steps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(?float $budget): Travel
    {
        $this->budget = $budget;

        return $this;
    }

    public function getIsShared(): ?bool
    {
        return $this->isShared;
    }

    public function setIsShared(bool $isShared): Travel
    {
        $this->isShared = $isShared;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Travel
    {
        $this->description = $description;

        return $this;
    }

    public function getStartedAt(): ?DateTime
    {
        return $this->startedAt;
    }

    public function setStartedAt(DateTime $startedAt): Travel
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?DateTime
    {
        return $this->endedAt;
    }

    public function setEndedAt(DateTime $endedAt): Travel
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): Travel
    {
        $this->userId = $userId;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Travel
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): Travel
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Travel
    {
        $this->name = $name;

        return $this;
    }

    // FIXME Move or delete this
    /**
     * @return Collection|Album[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): Travel
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
            $album->setTravelId($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): Travel
    {
        if ($this->albums->removeElement($album)) {
            // set the owning side to null (unless already changed)
            if ($album->getTravelId() === $this) {
                $album->setTravelId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Step[]
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): Travel
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setTravelId($this);
        }

        return $this;
    }

    public function removeStep(Step $step): Travel
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getTravelId() === $this) {
                $step->setTravelId(null);
            }
        }

        return $this;
    }
}
