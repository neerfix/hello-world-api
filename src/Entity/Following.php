<?php

namespace App\Entity;

use App\Repository\FollowingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity(repositoryClass="FollowingRepository")
 */
class Following
{

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="User", inversedBy="followings")
     * @JoinColumn(name="main_user_id", referencedColumnName="id")
     */
    private User $idMainUser;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="User", inversedBy="followers")
     * @JoinColumn(name="follower_id", referencedColumnName="id")
     */
    private User $idFollower;

    /**
     * @ORM\Column(name="status", type="string", length="255")
     */
    private string $status;

    /**
     * @ORM\Column(name="created_at", type="date")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="date")
     */
    private DateTime $updatedAt;

    public function getIdMainUser(): ?User
    {
        return $this->idMainUser;
    }

    public function setIdMainUser(?User $idMainUser): Following
    {
        $this->idMainUser = $idMainUser;

        return $this;
    }

    public function getIdFollower(): ?User
    {
        return $this->idFollower;
    }

    public function setIdFollower(?User $idFollower): Following
    {
        $this->idFollower = $idFollower;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): Following
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): Following
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): Following
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
