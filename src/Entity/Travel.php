<?php

namespace App\Entity;

use App\Repository\TravelRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TravelRepository::class")
 */
class Travel
{
    // -------------------------- >

    public function __construct()
    {
        $this->albums = new ArrayCollection();
        $this->steps = new ArrayCollection();

        // TODO move it from all Entity to AbstractEntity
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
    }

    // -------------------------- >

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer", unique=true)
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
    private ?DateTime $startedAt = null;

    /**
     * @ORM\Column(name="ended_at", type="date")
     */
    private ?DateTime $endedAt = null;

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
     * @ORM\OneToMany(targetEntity="Album", mappedBy="travelId")
     */
    private Collection $albums;

    /**
     * @ORM\OneToMany(targetEntity="Step", mappedBy="travelId")
     */
    private Collection $steps;

    // -------------------------- >

    public function getId(): int
    {
        return $this->id;
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

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(?float $budget = null): Travel
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

    public function setStartedAt(?DateTime $startedAt = null): Travel
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?DateTime
    {
        return $this->endedAt;
    }

    public function setEndedAt(?DateTime $endedAt = null): Travel
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

    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function getSteps(): Collection
    {
        return $this->steps;
    }
}
