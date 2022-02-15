<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $pseudo;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $phone_number;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'boolean')]
    private $is_verified;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updated_at;

    #[ORM\Column(type: 'string', length: 255)]
    private $profile_Ãpicture;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Login::class)]
    private $logins;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Travel::class)]
    private $travel;

    #[ORM\OneToMany(mappedBy: 'id_main_user', targetEntity: Following::class)]
    private $followings;

    #[ORM\OneToMany(mappedBy: 'id_follower', targetEntity: Following::class)]
    private $followers;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: File::class)]
    private $files;

    #[ORM\OneToOne(targetEntity: File::class, cascade: ['persist', 'remove'])]
    private $profile_picture;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Album::class)]
    private $albums;

    public function __construct()
    {
        $this->logins = new ArrayCollection();
        $this->travel = new ArrayCollection();
        $this->followings = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->albums = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): self
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getProfileÃpicture(): ?string
    {
        return $this->profile_Ãpicture;
    }

    public function setProfileÃpicture(string $profile_Ãpicture): self
    {
        $this->profile_Ãpicture = $profile_Ãpicture;

        return $this;
    }

    /**
     * @return Collection|Login[]
     */
    public function getLogins(): Collection
    {
        return $this->logins;
    }

    public function addLogin(Login $login): self
    {
        if (!$this->logins->contains($login)) {
            $this->logins[] = $login;
            $login->setUserId($this);
        }

        return $this;
    }

    public function removeLogin(Login $login): self
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
     * @return Collection|Travel[]
     */
    public function getTravel(): Collection
    {
        return $this->travel;
    }

    public function addTravel(Travel $travel): self
    {
        if (!$this->travel->contains($travel)) {
            $this->travel[] = $travel;
            $travel->setUserId($this);
        }

        return $this;
    }

    public function removeTravel(Travel $travel): self
    {
        if ($this->travel->removeElement($travel)) {
            // set the owning side to null (unless already changed)
            if ($travel->getUserId() === $this) {
                $travel->setUserId(null);
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

    public function addFollowing(Following $following): self
    {
        if (!$this->followings->contains($following)) {
            $this->followings[] = $following;
            $following->setIdMainUser($this);
        }

        return $this;
    }

    public function removeFollowing(Following $following): self
    {
        if ($this->followings->removeElement($following)) {
            // set the owning side to null (unless already changed)
            if ($following->getIdMainUser() === $this) {
                $following->setIdMainUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Following[]
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(Following $follower): self
    {
        if (!$this->followers->contains($follower)) {
            $this->followers[] = $follower;
            $follower->setIdFollower($this);
        }

        return $this;
    }

    public function removeFollower(Following $follower): self
    {
        if ($this->followers->removeElement($follower)) {
            // set the owning side to null (unless already changed)
            if ($follower->getIdFollower() === $this) {
                $follower->setIdFollower(null);
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

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setUserId($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getUserId() === $this) {
                $file->setUserId(null);
            }
        }

        return $this;
    }

    public function getProfilePicture(): ?File
    {
        return $this->profile_picture;
    }

    public function setProfilePicture(?File $profile_picture): self
    {
        $this->profile_picture = $profile_picture;

        return $this;
    }

    /**
     * @return Collection|Album[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
            $album->setUserId($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->removeElement($album)) {
            // set the owning side to null (unless already changed)
            if ($album->getUserId() === $this) {
                $album->setUserId(null);
            }
        }

        return $this;
    }
}
