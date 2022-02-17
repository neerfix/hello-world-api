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

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updated_at;

    #[ORM\Column(type: 'integer')]
    private $sequence;

    #[ORM\ManyToOne(targetEntity: File::class, inversedBy: 'albumFiles')]
    #[ORM\JoinColumn(nullable: false)]
    private $file_id;

    #[ORM\ManyToOne(targetEntity: Album::class, inversedBy: 'albumFiles')]
    #[ORM\JoinColumn(nullable: false)]
    private $album_id;

    public function __construct()
    {
        $this->album_id = new ArrayCollection();
        $this->file_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFileId(): ?File
    {
        return $this->file_id;
    }

    public function setFileId(?File $file_id): self
    {
        $this->file_id = $file_id;

        return $this;
    }

    public function getAlbumId(): ?Album
    {
        return $this->album_id;
    }

    public function setAlbumId(?Album $album_id): self
    {
        $this->album_id = $album_id;

        return $this;
    }
}
