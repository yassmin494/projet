<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\TripDay;
use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $fullName = null;

    #[ORM\Column(length: 16)]
    private ?string $cardNumber = null;

    #[ORM\Column(length: 5)]
    private ?string $expiry = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $cvv = null;

    #[ORM\Column(nullable: true)]
    private ?float $amount = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?TripDay $tripDay = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber): static
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    public function getExpiry(): ?string
    {
        return $this->expiry;
    }

    public function setExpiry(string $expiry): static
    {
        $this->expiry = $expiry;
        return $this;
    }

    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(?string $cvv): static
    {
        $this->cvv = $cvv;
        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getTripDay(): ?TripDay
    {
        return $this->tripDay;
    }

    public function setTripDay(?TripDay $tripDay): static
    {
        $this->tripDay = $tripDay;
        return $this;
    }
}
