<?php

namespace App\Entity;

use App\Entity\Interfaces\Statuable;
use App\Entity\Traits\StatuableTrait;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Table(name="user", indexes={
 *     @ORM\Index(name="status_idx", columns={ "status" })
 * })
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, Statuable
{
    use StatuableTrait;

    // ------------------------- >

    public function __construct()
    {
        $this->travels = new ArrayCollection();
        $this->logins = new ArrayCollection();
        $this->followings = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->wishLists = new ArrayCollection();
    }

    public const STATUS_ACTIVE = 'active'; // Active account
    public const STATUS_BANNED = 'banned'; // Banned account (by admin)
    public const STATUS_DELETED = 'deleted'; // Deleted account (by user)

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_PARTNER = 'ROLE_PARTNER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    // ------------------------- >

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer", unique=true)
     */
    private int $id;

    /**
     * @Groups({
     *     "user:read",
     *     "user:search",
     *     "travel:nested",
     *     "file:nested"
     * })
     * @ORM\Column(name="uuid", type="string", length="180", unique=true)
     */
    private string $uuid;

    /**
     * @Groups({
     *     "user:read",
     *     "travel:nested",
     *     "file:nested"
     * })
     * @ORM\Column(name="roles", type="json")
     */
    private array $roles = [];

    /**
     * @Ignore()
     * @ORM\Column(name="password", type="string")
     */
    private string $password;

    /**
     * @Groups({
     *     "user:read",
     *     "user:search",
     *     "travel:nested",
     *     "file:nested"
     * })
     * @ORM\Column(name="email", type="string", length="255", unique=true)
     */
    private string $email;

    /**
     * @Groups({
     *     "user:read",
     *     "travel:nested",
     *     "file:nested"
     * })
     * @ORM\Column(name="date_of_birth", type="date")
     */
    private DateTime $dateOfBirth;

    /**
     * @Groups({
     *     "user:read",
     *     "user:search",
     *     "travel:nested",
     *     "file:nested"
     * })
     * @ORM\Column(name="firstname", type="string", length="50", nullable=true)
     */
    private ?string $firstname = null;

    /**
     * @Groups({
     *     "user:read",
     *     "user:search",
     *     "travel:nested",
     *     "file:nested"
     * })
     * @ORM\Column(name="lastname", type="string", length="70", nullable=true)
     */
    private ?string $lastname = null;

    /**
     * @Groups({
     *     "user:read",
     *     "user:search",
     *     "travel:nested",
     *     "file:nested"
     * })
     * @ORM\Column(name="username", type="string", length="55", unique=true)
     */
    private string $username;

    /**
     * @Groups({
     *     "user:read",
     *     "user:search",
     *     "travel:nested",
     *     "file:nested"
     * })
     * @ORM\Column(name="is_verify", type="boolean")
     */
    private bool $isVerify;

    /**
     * @Groups({
     *     "user:read",
     * })
     * @ORM\OneToMany(targetEntity="Travel", mappedBy="userId")
     */
    private Collection $travels;

    /**
     * @Groups({
     *     "user:read",
     * })
     * @ORM\OneToMany(targetEntity="Login", mappedBy="userId")
     */
    private Collection $logins;

    /**
     * @Groups({
     *     "user:read",
     * })
     * @ORM\OneToMany(targetEntity="Following", mappedBy="mainUserId")
     */
    private Collection $followings;

    /**
     * @Groups({
     *     "user:read",
     * })
     * @ORM\OneToMany(targetEntity="Following", mappedBy="followerId")
     */
    private Collection $followers;

    /**
     * @Groups({
     *     "user:read",
     * })
     * @ORM\OneToMany(targetEntity="File",mappedBy="userId")
     */
    private Collection $files;

    /**
     * @Groups({
     *     "user:read",
     * })
     * @ORM\OneToMany(targetEntity="WishList", mappedBy="user")
     */
    private Collection $wishLists;

    // ------------------------- >

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): User
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        array_push($roles, static::ROLE_USER);

        return array_unique($roles);
    }

    public function setRoles(array $roles): User
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getDateOfBirth(): DateTime
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(DateTime $dateOfBirth): User
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): User
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): User
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    public function getIsVerify(): bool
    {
        return $this->isVerify;
    }

    public function setIsVerify(bool $isVerify): User
    {
        $this->isVerify = $isVerify;

        return $this;
    }

    public function getTravels(): Collection
    {
        return $this->travels;
    }

    public function getLogins(): Collection
    {
        return $this->logins;
    }

    public function getFollowings(): Collection
    {
        return $this->followings;
    }

    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function getWishLists(): Collection
    {
        return $this->wishLists;
    }
}
