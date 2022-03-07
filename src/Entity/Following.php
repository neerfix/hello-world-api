<?php

namespace App\Entity;

use App\Repository\FollowingRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FollowingRepository::class)
 */
class Following
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="followings")
     * @ORM\JoinColumn(name="main_user_id", referencedColumnName="id")
     */
    private User $mainUserId;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="followers")
     * @ORM\JoinColumn(name="follower_id", referencedColumnName="id")
     */
    private User $followerId;

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

    public function getMainUserId(): ?User
    {
        return $this->mainUserId;
    }

    public function setMainUserId(?User $mainUserId): Following
    {
        $this->mainUserId = $mainUserId;

        return $this;
    }

    public function getFollowerId(): ?User
    {
        return $this->followerId;
    }

    public function setFollowerId(?User $followerId): Following
    {
        $this->followerId = $followerId;

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

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Following
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): Following
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
