<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // ------------------------- >

    public function __construct()
    {
        $this->travels = new ArrayCollection();
        $this->logins = new ArrayCollection();
        $this->followings = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    // ------------------------- >

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="string", unique=true)
     */
    private int $id;

    /**
     * @ORM\Column(name="uuid", type="string", length="180", unique=true)
     */
    private string $uuid;

    /**
     * @ORM\Column(name="roles", type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(name="password", type="string")
     */
    private string $password;

    /**
     * @ORM\Column(name="email", type="string", length="255", unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(name="date_of_birth", type="date")
     */
    private DateTime $dateOfBirth;

    /**
     * @ORM\Column(name="firstname", type="string", length="50", nullable=true)
     */
    private ?string $firstname = null;

    /**
     * @ORM\Column(name="lastname", type="string", length="70", nullable=true)
     */
    private ?string $lastname = null;

    /**
     * @ORM\Column(name="username", type="string", length="55", unique=true)
     */
    private string $username;

    /**
     * @ORM\Column(name="is_verify", type="string", length="10")
     */
    private bool $isVerify;

    /**
     * @ORM\OneToMany(targetEntity="Travel", mappedBy="userId")
     */
    private Collection $travels;

    /**
     * @ORM\OneToMany(targetEntity="Login", mappedBy="userId")
     */
    private Collection $logins;

    /**
     * @ORM\OneToMany(targetEntity="Following", mappedBy="mainUserId")
     */
    private Collection $followings;

    /**
     * @ORM\OneToMany(targetEntity="Following", mappedBy="followerId")
     */
    private Collection $followers;

    /**
     * @ORM\OneToMany(targetEntity="File",mappedBy="userId")
     */
    private Collection $files;

    // ------------------------- >

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
        $roles[] = 'ROLE_USER';

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

    public function getDateOfBirth(): ?DateTime
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

    public function setFirstname(string $firstname): User
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): User
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

    public function getIsVerify(): ?bool
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
}
