<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\Index(columns: ['lane_id', 'start_time', 'end_time'], name: 'idx_availability')]
#[ORM\HasLifecycleCallbacks]
class Reservation
{
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PENDING = 'pending';
    public const STATUS_CANCELLED = 'cancelled';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: true)]
    public ?User $user = null;

    #[ORM\Column(length: 100, nullable: true)]
    public ?string $name = null;

    #[ORM\Column(length: 180, nullable: true)]
    public ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    public ?string $phone = null;

    #[ORM\ManyToOne(targetEntity: Lane::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Lane $lane = null;

    #[ORM\ManyToOne(targetEntity: Tariff::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Tariff $tariff = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    public ?string $appliedRate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public ?\DateTimeInterface $endTime = null;

    #[ORM\Column]
    public int $numberOfAdults = 1;

    #[ORM\Column]
    public int $numberOfChildren = 0;

    #[ORM\ManyToOne(targetEntity: Package::class)]
    public ?Package $snackPackage = null;

    #[ORM\ManyToOne(targetEntity: Package::class)]
    public ?Package $partyPackage = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    public ?string $totalPrice = null;

    #[ORM\Column(length: 20)]
    public string $status = self::STATUS_CONFIRMED;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function __toString(): string
    {
        return 'Reservation #' . $this->id . ' - ' . $this->lane . ' (' . $this->startTime?->format('d/m/Y H:i') . ')';
    }
}
