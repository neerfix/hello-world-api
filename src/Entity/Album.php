<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer", length="180", unique=true)
     */
    private int $id;

    /**
     * @ORM\Column(name="title", type="string", length="255")
     */
    private string $title;

    /**
     * @ORM\Column(name="description", type="string", length="255", nullable=true)
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="albums")
     */
    private User $userId;

    /**
     * @ORM\ManyToOne(targetEntity="Travel", inversedBy="albums")
     */
    private Travel $travelId;

    /**
     * @ORM\Column(name="created_at", type="date")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="date")
     */
    private DateTime $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="Step", inversedBy="albums")
     * @JoinTable(name="step_albums",
     * joinColumns={@JoinColumn(name="album_id",referencedColumnName="id")},
     * inverseJoinColumns={@JoinColumn(name="step_id", referencesColumnName="id")}
     */
    private Collection $steps;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->albumFiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): Album
    {
        $this->userId = $userId;

        return $this;
    }

    public function getTravelId(): ?Travel
    {
        return $this->travelId;
    }

    public function setTravelId(?Travel $travel_id): self
    {
        $this->travelId = $travelId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updated_at): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Step[]
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): Album
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->addAlbumId($this);
        }

        return $this;
    }

    public function removeStep(Step $step): Album
    {
        if ($this->steps->removeElement($step)) {
            $step->removeAlbumId($this);
        }

        return $this;
    }
}
