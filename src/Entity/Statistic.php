<?php

namespace App\Entity;

use App\Repository\StatisticRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatisticRepository::class)]
class Statistic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbClients = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbReservations = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalRevenue = null;

    #[ORM\Column(nullable: true)]
    private ?array $topServices = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getNbClients(): ?int
    {
        return $this->nbClients;
    }

    public function setNbClients(?int $nbClients): static
    {
        $this->nbClients = $nbClients;

        return $this;
    }

    public function getNbReservations(): ?int
    {
        return $this->nbReservations;
    }

    public function setNbReservations(?int $nbReservations): static
    {
        $this->nbReservations = $nbReservations;

        return $this;
    }

    public function getTotalRevenue(): ?float
    {
        return $this->totalRevenue;
    }

    public function setTotalRevenue(?float $totalRevenue): static
    {
        $this->totalRevenue = $totalRevenue;

        return $this;
    }

    public function getTopServices(): ?array
    {
        return $this->topServices;
    }

    public function setTopServices(?array $topServices): static
    {
        $this->topServices = $topServices;

        return $this;
    }
}
