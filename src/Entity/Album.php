<?php

namespace App\Entity;

use App\Entity\Interfaces\Statuable;
use App\Entity\Traits\StatuableTrait;
use App\Repository\AlbumRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="album", indexes={
 *     @ORM\Index(name="status_idx", columns={ "status" })
 * })
 * @ORM\Entity(repositoryClass=AlbumRepository::class)
 */
class Album implements Statuable
{
    use StatuableTrait;

    // -------------------------- >

    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->albumFiles = new ArrayCollection();

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
     * @ORM\Column(name="uuid", type="string", length="180", unique=true)
     * @Groups("album:read")
     */
    private string $uuid;

    /**
     * @ORM\Column(name="title", type="string", length="255")
     * @Groups({
     *     "album:read",
     *     "step:nested",
     * })
     */
    private string $title;

    /**
     * @ORM\Column(name="description", type="string", length="255", nullable=true)
     * @Groups({
     *     "album:read",
     *     "step:nested",
     * })
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity="Travel", inversedBy="albums")
     * @ORM\JoinColumn(name="travel_id", referencedColumnName="id")
     * @Groups("album:read")
     */
    private Travel $travel;

    /**
     * @ORM\OneToOne(targetEntity="Step", mappedBy="albumId")
     * @Groups("album:read")
     */
    private Step $step;

    /**
     * @ORM\Column(name="created_at", type="date")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="date")
     */
    private DateTime $updatedAt;

    // -------------------------- >

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): Album
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Album
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Album
    {
        $this->description = $description;

        return $this;
    }

    public function getTravel(): ?Travel
    {
        return $this->travel;
    }

    public function setTravel(?Travel $travel): Album
    {
        $this->travel = $travel;

        return $this;
    }

    public function getStep(): ?Step
    {
        return $this->step;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Album
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): Album
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
