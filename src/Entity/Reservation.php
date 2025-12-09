<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'reservation')]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // relation with Service
    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Service $service = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $clientName = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $clientEmail = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull]
    private \DateTimeInterface $startDate;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull]
    private \DateTimeInterface $endDate;

    #[ORM\Column(type: 'string', length: 50)]
    private string $status = 'pending';

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    // getters & setters...
    public function getId(): ?int { return $this->id; }
    public function getService(): ?Service { return $this->service; }
    public function setService(Service $s): self { $this->service = $s; return $this; }
    public function getClientName(): ?string { return $this->clientName; }
    public function setClientName(string $n): self { $this->clientName = $n; return $this; }
    public function getClientEmail(): ?string { return $this->clientEmail; }
    public function setClientEmail(?string $e): self { $this->clientEmail = $e; return $this; }
    public function getStartDate(): \DateTimeInterface { return $this->startDate; }
    public function setStartDate(\DateTimeInterface $d): self { $this->startDate = $d; return $this; }
    public function getEndDate(): \DateTimeInterface { return $this->endDate; }
    public function setEndDate(\DateTimeInterface $d): self { $this->endDate = $d; return $this; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $s): self { $this->status = $s; return $this; }
    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
}
