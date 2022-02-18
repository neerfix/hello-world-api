<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="FileRepository")
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer", length="180", unique=true)
     */
    private int $id;

    /**
     * @ORM\Column(name="extension", type="string", length="255")
     */
    private string $extension;

    /**
     * @ORM\Column(name="created_at", type="date")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="date")
     */
    private DateTime $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="files")
     * @ORM\Column(name="user_id", nullable="false")
     */
    private User $userId;

    /**
     * @ORM\Column(name="uuid", type="string", length="255")
     */
    private string $uuid;

    /**
     * @ORM\Column(name="type", type="string", length="255")
     */
    private string $type;

    /**
     * @ORM\Column(name="mime_type", type="string", length="255")
     */
    private string $mimeType;

    /**
     * @ORM\Column(name="size", type="integer")
     */
    private int $size;

    /**
     * @ORM\OneToMany(targetEntity="AlbumFile", mappedBy="file_id")
     */
    private Collection $albumFiles;

    public function __construct()
    {
        $this->albumFiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): File
    {
        $this->extension = $extension;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): File
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): File
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): File
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): File
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): File
    {
        $this->type = $type;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): File
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): File
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return Collection|AlbumFile[]
     */
    public function getAlbumFiles(): Collection
    {
        return $this->albumFiles;
    }

    public function addAlbumFile(AlbumFile $albumFile): File
    {
        if (!$this->albumFiles->contains($albumFile)) {
            $this->albumFiles[] = $albumFile;
            $albumFile->setFileId($this);
        }

        return $this;
    }

    public function removeAlbumFile(AlbumFile $albumFile): File
    {
        if ($this->albumFiles->removeElement($albumFile)) {
            // set the owning side to null (unless already changed)
            if ($albumFile->getFileId() === $this) {
                $albumFile->setFileId(null);
            }
        }

        return $this;
    }
}
