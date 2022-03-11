<?php

namespace App\Entity;

use App\Entity\Interfaces\Statuable;
use App\Entity\Traits\StatuableTrait;
use App\Repository\WishListRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="wishlist", indexes={
 *     @ORM\Index(name="status_idx", columns={ "status" })
 * })
 * @ORM\Entity(repositoryClass=WishListRepository::class)
 */
class WishList implements Statuable
{
    use StatuableTrait;

    public const STATUS_DELETED = 'deleted';
    public const STATUS_ACTIVE = 'active';

    // -------------------------- >

    public function __construct()
    {
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
     * @Groups("wishList:read")
     */
    private string $uuid;

    /**
     * @ORM\Column(name="name", type="string", length="255")
     * @Groups("wishList:read")
     */
    private string $name;

    /**
     * @ORM\Column(name="description", type="string", length="255", nullable=true)
     * @Groups("wishList:read")
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="wishLists")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     * @Groups("wishList:read")
     */
    private Place $place;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="wishLists")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User $user;

    /**
     * @ORM\Column(name="estimated_at", type="date")
     * @Groups("wishList:read")
     */
    private DateTime $estimatedAt;

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

    public function setUuid(string $uuid): WishList
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): WishList
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): WishList
    {
        $this->description = $description;

        return $this;
    }

    public function getPlace(): Place
    {
        return $this->place;
    }

    public function setPlace(Place $place): WishList
    {
        $this->place = $place;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): WishList
    {
        $this->user = $user;

        return $this;
    }

    public function getEstimatedAt(): ?DateTime
    {
        return $this->estimatedAt;
    }

    public function setEstimatedAt(DateTime $estimatedAt): WishList
    {
        $this->estimatedAt = $estimatedAt;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): WishList
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): WishList
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
