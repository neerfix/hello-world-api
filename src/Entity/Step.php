<?php

namespace App\Entity;

use App\Entity\Interfaces\Statuable;
use App\Entity\Traits\StatuableTrait;
use App\Repository\StepRepository;
use DateTime;
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

    // ----------------------- >

    public const STATUS_DELETED = 'deleted';
    public const STATUS_ACTIVE = 'active';

    // ----------------------- >

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer", unique=true)
     */
    private int $id;

    /**
     * @ORM\Column(name="uuid", type="string", length="180", unique=true)
     * @Groups("step:read")
     */
    private string $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="Travel", inversedBy="steps")
     * @ORM\JoinColumn(name="travel_id", referencedColumnName="id", nullable="false")
     * @Groups("step:read")
     */
    private Travel $travel;

    /**
     * @ORM\OneToOne(targetEntity="Album", inversedBy="step")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id")
     * @Groups("step:read")
     */
    private Album $album;

    /**
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="steps")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     * @Groups("step:read")
     */
    private Place $place;

    /**
     * @ORM\Column(name="started_at", type="date", nullable="true")
     * @Groups("step:read")
     */
    private ?DateTime $startedAt = null;

    /**
     * @ORM\Column(name="ended_at", type="date", nullable="true")
     * @Groups("step:read")
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

    public function getTravel(): Travel
    {
        return $this->travel;
    }

    public function setTravel(Travel $travel): Step
    {
        $this->travel = $travel;

        return $this;
    }

    public function getAlbum(): Album
    {
        return $this->album;
    }

    public function setAlbum(Album $album): Step
    {
        $this->album = $album;

        return $this;
    }

    public function removeAlbum(Album $album): Step
    {
        $this->album->removeElement($album);

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): Step
    {
        $this->place = $place;

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
