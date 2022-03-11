<?php

namespace App\Entity;

use App\Entity\Interfaces\Statuable;
use App\Entity\Traits\StatuableTrait;
use App\Repository\PlaceRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="place", indexes={
 *     @ORM\Index(name="status_idx", columns={ "status" })
 * })
 * @ORM\Entity(repositoryClass=PlaceRepository::class)
 */
class Place implements Statuable
{
    use StatuableTrait;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_DELETED = 'deleted';

    // -------------------------- >

    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->wishLists = new ArrayCollection();
        $this->travels = new ArrayCollection();

        $this->setUpdatedAt(new DateTime());
        $this->setCreatedAt(new DateTime());
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
     *     "place:read",
     *     "travel:read"
     * })
     * @ORM\Column(name="uuid", type="string", length="180", unique=true)
     */
    private string $uuid;

    /**
     * @Groups({
     *     "place:read",
     *     "step:nested",
     *     "wishList:nested",
     *     "travel:read"
     * })
     * @ORM\Column(name="address", type="string", length="255",nullable=true)
     */
    private ?string $address;

    /**
     * @Groups({
     *   "place:read",
     *    "step:nested",
     *    "wishList:nested",
     *     "travel:read"
     * })
     * @ORM\Column(name="city", type="string", length="255", nullable=true)
     */
    private ?string $city;

    /**
     * @Groups({
     *   "place:read",
     *   "step:nested",
     *   "wishList:nested",
     *     "travel:read"
     * })
     * @ORM\Column(name="zipcode", type="string", length="255", nullable=true)
     */
    private ?string $zipcode;

    /**
     * @Groups({
     *   "place:read",
     *    "step:nested",
     *    "wishList:nested",
     *     "travel:read"
     * })
     * @ORM\Column(name="country", type="string", length="255",nullable=true)
     */
    private ?string $country;

    /**
     * @Groups({
     *     "place:read",
     *     "step:nested",
     *     "wishList:nested",
     *     "travel:read",
     * })
     * @ORM\Column(name="name", type="string", length="255")
     */
    private string $name;

    /**
     * @Groups({
     *     "place:read",
     *     "step:nested",
     *     "wishList:nested",
     *     "travel:read"
     * })
     * @ORM\Column(name="latitude", type="string", length="255")
     */
    private string $latitude;

    /**
     * @Groups({
     *     "place:read",
     *     "step:nested",
     *     "wishList:nested",
     *     "travel:read"
     * })
     * @ORM\Column(name="longitude", type="string", length="255")
     */
    private string $longitude;

    /**
     * @Groups({
     *   "place:read"
     * })
     * @ORM\Column(name="created_at", type="date")
     */
    private DateTime $createdAt;

    /**
     * @Groups({
     *   "place:read"
     * })
     * @ORM\Column(name="updated_at", type="date")
     */
    private DateTime $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="Step", mappedBy="id")
     */
    private Collection $steps;

    /**
     * @ORM\OneToMany(targetEntity="WishList", mappedBy="place")
     */
    private Collection $wishLists;

    /**
     * @ORM\OneToMany(targetEntity="Travel", mappedBy="placeId")
     */
    private Collection $travels;

    // -------------------------- >

    public function getId(): int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): Place
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): Place
    {
        $this->city = $city;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): Place
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): Place
    {
        $this->country = $country;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Place
    {
        $this->name = $name;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): Place
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): Place
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Place
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): Place
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): Place
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getWishLists(): Collection
    {
        return $this->wishLists;
    }
}
