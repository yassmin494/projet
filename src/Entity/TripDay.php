<?php

namespace App\Entity;

use App\Repository\TripDayRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripDayRepository::class)]
class TripDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $destination = null;

    #[ORM\Column]
    private ?int $dayNumber = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: 'json')]
    private array $image = [];

    #[ORM\Column]
    private ?int $price = null;  // <-- Ajouté !

    // --------------------------
    //        GETTERS/SETTERS
    // --------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;
        return $this;
    }

    public function getDayNumber(): ?int
    {
        return $this->dayNumber;
    }

    public function setDayNumber(int $dayNumber): static
    {
        $this->dayNumber = $dayNumber;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getImage(): array
    {
        return $this->image ?? [];
    }

    public function setImage(array $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getPrice(): ?int  // <-- Ajouté !
    {
        return $this->price;
    }

    public function setPrice(int $price): static  // <-- Ajouté !
    {
        $this->price = $price;
        return $this;
    }
}
