<?php

namespace App\Entity;

use App\Entity\Interfaces\Statuable;
use App\Entity\Traits\StatuableTrait;
use App\Repository\AlbumFileRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user", indexes={
 *     @Index(name="status_idx", columns={ "status" })
 * })
 * @ORM\Entity(repositoryClass="AlbumFileRepository")
 */
class AlbumFile implements Statuable
{
    use StatuableTrait;
    // -------------------------- >

    public function __construct()
    {
        $this->albumId = new ArrayCollection();
        $this->fileId = new ArrayCollection();
    }

    // -------------------------- >

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="File")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id")
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

    // -------------------------- >

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): AlbumFile
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): AlbumFile
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
