<?php

namespace App\Entity;

use App\Entity\Interfaces\Statuable;
use App\Entity\Traits\StatuableTrait;
use App\Repository\TravelRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="travel", indexes={
 *     @ORM\Index(name="status_idx", columns={ "status" })
 * })
 * @ORM\Entity(repositoryClass=TravelRepository::class)
 */
class Travel implements Statuable
{
    use StatuableTrait;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_PENDING = 'pending';
    public const STATUS_INCOMING = 'incoming';
    public const STATUS_STARTED = 'started';
    public const STATUS_ENDED = 'ended';
    public const STATUS_DELETED = 'deleted';

    // -------------------------- >

    public function __construct()
    {
        $this->albums = new ArrayCollection();
        $this->steps = new ArrayCollection();

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
     * @Groups({
     *     "travel:read",
     * })
     * @ORM\Column(name="uuid", type="string", length="180", unique=true)
     */
    private string $uuid;

    /**
     * @Groups({
     *     "travel:read",
     *     "album:nested",
     *     "step:nested",
     * })
     * @ORM\Column(name="name", type="string", length="255")
     */
    private string $name;

    /**
     * @Groups("travel:read")
     * @ORM\Column(name="budget", type="string", length="255")
     */
    private string $budget;

    /**
     * @Groups("travel:read")
     * @ORM\Column(name="is_shared", type="boolean")
     */
    private bool $isShared = true;

    /**
     * @Groups("travel:read")
     * @ORM\Column(name="description", type="text")
     */
    private ?string $description;

    /**
     * @Groups("travel:read")
     * @ORM\Column(name="started_at", type="date", nullable="true")
     */
    private ?DateTime $startedAt = null;

    /**
     * @Groups("travel:read")
     * @ORM\Column(name="ended_at", type="date", nullable="true")
     */
    private ?DateTime $endedAt = null;

    /**
     * @Groups("travel:read")
     * @ORM\ManyToOne(targetEntity="User", inversedBy="travels")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable="false")
     */
    private User $userId;

    /**
     * @Groups("travel:read")
     * @ORM\Column(name="created_at", type="date")
     */
    private DateTime $createdAt;

    /**
     * @Groups("travel:read")
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

    /**
     * @Groups("travel:read")
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="travels")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id", nullable="false")
     */
    private Place $placeId;

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

    public function getBudget(): float
    {
        return $this->budget;
    }

    public function setBudget(float $budget = null): Travel
    {
        $this->budget = $budget;

        return $this;
    }

    public function getIsShared(): bool
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

    public function getUserId(): User
    {
        return $this->userId;
    }

    public function setUserId(User $userId): Travel
    {
        $this->userId = $userId;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Travel
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTime
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

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): Travel
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function getPlaceId(): Place
    {
        return $this->placeId;
    }

    public function setPlaceId(Place $placeId): Travel
    {
        $this->placeId = $placeId;

        return $this;
    }
}
