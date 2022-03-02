<?php

namespace App\Entity;

use App\Entity\Interfaces\Statuable;
use App\Entity\Traits\StatuableTrait;
use App\Repository\LoginRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user", indexes={
 *     @Index(name="status_idx", columns={ "status" })
 * })
 * @ORM\Entity(repositoryClass="LoginRepository")
 */
class Login implements Statuable
{
    use StatuableTrait;

    // -------------------------- >

    public function __construct()
    {
        $this->setUpdatedAt(new DateTime());
        $this->setCreatedAt(new DateTime());
    }

    // -------------------------- >

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer", unique=true)
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="logins")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User $userId;

    /**
     * @ORM\Column(name="application_version", type="string", length="255")
     */
    private string $applicationVersion;

    /**
     * @ORM\Column(name="ip_address", type="string", length="255")
     */
    private string $ipAddress;

    /**
     * @ORM\Column(name="user_agent", type="string", length="255")
     */
    private string $userAgent;

    /**
     * @ORM\Column(name="is_successful", type="boolean")
     */
    private bool $isSuccessful;

    /**
     * @ORM\Column(name="failure_reason", type="string", length="255")
     */
    private string $failureReason;

    /**
     * @ORM\Column(name="created_at", type="date")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="date")
     */
    private DateTime $updatedAt;

    // -------------------------- >

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): User
    {
        return $this->userId;
    }

    public function setUserId(User $user): Login
    {
        $this->userId = $user;

        return $this;
    }

    public function getApplicationVersion(): ?string
    {
        return $this->applicationVersion;
    }

    public function setApplicationVersion(?string $applicationVersion): Login
    {
        $this->applicationVersion = $applicationVersion;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): Login
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): Login
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getIsSuccessful(): ?bool
    {
        return $this->isSuccessful;
    }

    public function setIsSuccessful(?bool $isSuccessful): Login
    {
        $this->isSuccessful = $isSuccessful;

        return $this;
    }

    public function getFailureReason(): ?string
    {
        return $this->failureReason;
    }

    public function setFailureReason(?string $failureReason): Login
    {
        $this->failureReason = $failureReason;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Login
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): Login
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
