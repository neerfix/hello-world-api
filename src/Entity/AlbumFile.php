<?php

namespace App\Entity;

use App\Repository\AlbumFileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumFileRepository::class)]
class AlbumFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'albumFiles')]
    private $album_id;

    #[ORM\ManyToMany(targetEntity: File::class, inversedBy: 'albumFiles')]
    private $file_id;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updated_at;

    #[ORM\Column(type: 'integer')]
    private $sequence;

    public function __construct()
    {
        $this->album_id = new ArrayCollection();
        $this->file_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Album[]
     */
    public function getAlbumId(): Collection
    {
        return $this->album_id;
    }

    public function addAlbumId(Album $albumId): self
    {
        if (!$this->album_id->contains($albumId)) {
            $this->album_id[] = $albumId;
        }

        return $this;
    }

    public function removeAlbumId(Album $albumId): self
    {
        $this->album_id->removeElement($albumId);

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getFileId(): Collection
    {
        return $this->file_id;
    }

    public function addFileId(File $fileId): self
    {
        if (!$this->file_id->contains($fileId)) {
            $this->file_id[] = $fileId;
        }

        return $this;
    }

    public function removeFileId(File $fileId): self
    {
        $this->file_id->removeElement($fileId);

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

    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    public function setSequence(int $sequence): self
    {
        $this->sequence = $sequence;

        return $this;
    }
}
