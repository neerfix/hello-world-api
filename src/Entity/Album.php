<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'albums')]
    #[ORM\JoinColumn(nullable: false)]
    private $user_id;

    #[ORM\ManyToOne(targetEntity: travel::class, inversedBy: 'albums')]
    #[ORM\JoinColumn(nullable: false)]
    private $travel_id;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updated_at;

    #[ORM\ManyToMany(targetEntity: Step::class, mappedBy: 'album_id')]
    private $steps;

    #[ORM\OneToMany(mappedBy: 'album_id', targetEntity: AlbumFile::class)]
    private $albumFiles;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->albumFiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getTravelId(): ?travel
    {
        return $this->travel_id;
    }

    public function setTravelId(?travel $travel_id): self
    {
        $this->travel_id = $travel_id;

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

    /**
     * @return Collection|Step[]
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->addAlbumId($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->removeElement($step)) {
            $step->removeAlbumId($this);
        }

        return $this;
    }

    /**
     * @return Collection|AlbumFile[]
     */
    public function getAlbumFiles(): Collection
    {
        return $this->albumFiles;
    }

    public function addAlbumFile(AlbumFile $albumFile): self
    {
        if (!$this->albumFiles->contains($albumFile)) {
            $this->albumFiles[] = $albumFile;
            $albumFile->setAlbumId($this);
        }

        return $this;
    }

    public function removeAlbumFile(AlbumFile $albumFile): self
    {
        if ($this->albumFiles->removeElement($albumFile)) {
            // set the owning side to null (unless already changed)
            if ($albumFile->getAlbumId() === $this) {
                $albumFile->setAlbumId(null);
            }
        }

        return $this;
    }
}
