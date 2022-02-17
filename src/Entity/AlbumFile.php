<?php

namespace App\Entity;

use App\Repository\AlbumFileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AlbumFileRepository")
 */
class AlbumFile
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="File")
     * @JoinColumn(name="file_id", referencedColumnName="id")
     */
    private File $fileId;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Album")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id")
     */
    private Album $albumId;

    /**
     * @ORM\Column(name="created_at", type="date")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="date")
     */
    private DateTime $updatedAt;

    /**
     * @ORM\Column(name="sequence", type="integer")
     */
    private int $sequence;

    public function __construct()
    {
        $this->album_id = new ArrayCollection();
        $this->file_id = new ArrayCollection();
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): AlbumFile
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): AlbumFile
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    public function setSequence(int $sequence): AlbumFile
    {
        $this->sequence = $sequence;

        return $this;
    }

    public function getFileId(): ?File
    {
        return $this->fileId;
    }

    public function setFileId(?File $fileId): AlbumFile
    {
        $this->fileId = $fileId;

        return $this;
    }

    public function getAlbumId(): ?Album
    {
        return $this->albumId;
    }

    public function setAlbumId(?Album $albumId): AlbumFile
    {
        $this->albumId = $albumId;

        return $this;
    }
}
