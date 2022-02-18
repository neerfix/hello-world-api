<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="string", length="180", unique=true)
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
    private string $firstname;

    /**
     * @ORM\Column(name="lastname", type="string", length="70", nullable=true)
     */
    private string $lastname;

    /**
     * @ORM\Column(name="username", type="string", length="55", unique=true)
     */
    private string $username;

    /**
     * @ORM\Column(name="is_verify", type="string", length="180")
     */
    private bool $isVerify;

    /**
     * @ORM\OneToMany(targetEntity="Travel", mappedBy="user_id")
     */
    private Collection $travels;

    /**
     * @ORM\OneToMany(targetEntity="Login", mappedBy="user_id")
     */
    private Collection $logins;

    /**
     * @ORM\OneToMany(targetEntity="Following", mappedBy="main_user_id")
     */
    private Collection $followings;

    /**
     * @ORM\OneToMany(targetEntity="Following", mappedBy="follower_id")
     */
    private Collection $followers;

    /**
     * @ORM\OneToMany(targetEntity="File",mappedBy="user_id")
     */
    private Collection $files;

    public function __construct()
    {
        $this->travels = new ArrayCollection();
        $this->logins = new ArrayCollection();
        $this->followings = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
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

    public function getEmail(): ?string
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

    public function getUsername(): ?string
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

    /**
     * @return Collection|Travel[]
     */
    public function getTravels(): Collection
    {
        return $this->travels;
    }

    public function addTravel(Travel $travel): User
    {
        if (!$this->travels->contains($travel)) {
            $this->travels[] = $travel;
            $travel->setUserId($this);
        }

        return $this;
    }

    public function removeTravel(Travel $travel): User
    {
        if ($this->travels->removeElement($travel)) {
            // set the owning side to null (unless already changed)
            if ($travel->getUserId() === $this) {
                $travel->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Login[]
     */
    public function getLogins(): Collection
    {
        return $this->logins;
    }

    public function addLogin(Login $login): User
    {
        if (!$this->logins->contains($login)) {
            $this->logins[] = $login;
            $login->setUserId($this);
        }

        return $this;
    }

    public function removeLogin(Login $login): User
    {
        if ($this->logins->removeElement($login)) {
            // set the owning side to null (unless already changed)
            if ($login->getUserId() === $this) {
                $login->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Following[]
     */
    public function getFollowings(): Collection
    {
        return $this->followings;
    }

    public function addFollowing(Following $following): User
    {
        if (!$this->followings->contains($following)) {
            $this->followings[] = $following;
            $following->setMainUserId($this);
        }

        return $this;
    }

    public function removeFollowing(Following $following): User
    {
        if ($this->followings->removeElement($following)) {
            // set the owning side to null (unless already changed)
            if ($following->getMainUserId() === $this) {
                $following->setMainUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Follower[]
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(Follower $follower): User
    {
        if (!$this->followers->contains($follower)) {
            $this->followers[] = $follower;
            $followers->setFollowerId($this);
        }

        return $this;
    }

    public function removeFollower(Follower $follower): User
    {
        if ($this->followers->removeElement($follower)) {
            // set the owning side to null (unless already changed)
            if ($follower->getFollowerId() === $this) {
                $follower->setFollowerId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): User
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setUserId($this);
        }

        return $this;
    }

    public function removeFile(File $file): User
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getUserId() === $this) {
                $file->setUserId(null);
            }
        }

        return $this;
    }
}
