<?php

namespace App\Entity;

use App\Repository\LoginRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoginRepository::class)]
class Login
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'logins')]
    #[ORM\JoinColumn(nullable: false)]
    private $user_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $application_version;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $ip_address;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $user_agent;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $is_successful;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $failureÃ_reason;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getApplicationVersion(): ?string
    {
        return $this->application_version;
    }

    public function setApplicationVersion(?string $application_version): self
    {
        $this->application_version = $application_version;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(?string $ip_address): self
    {
        $this->ip_address = $ip_address;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->user_agent;
    }

    public function setUserAgent(?string $user_agent): self
    {
        $this->user_agent = $user_agent;

        return $this;
    }

    public function getIsSuccessful(): ?bool
    {
        return $this->is_successful;
    }

    public function setIsSuccessful(?bool $is_successful): self
    {
        $this->is_successful = $is_successful;

        return $this;
    }

    public function getFailureÃReason(): ?string
    {
        return $this->failureÃ_reason;
    }

    public function setFailureÃReason(?string $failureÃ_reason): self
    {
        $this->failureÃ_reason = $failureÃ_reason;

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
}
