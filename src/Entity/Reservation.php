<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    public ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Lane::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Lane $lane = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public ?\DateTimeInterface $endTime = null;

    #[ORM\Column]
    public int $numberOfAdults = 1;

    #[ORM\Column]
    public int $numberOfChildren = 0;

    /** @var Collection<int, Package> */
    #[ORM\ManyToMany(targetEntity: Package::class, inversedBy: 'reservations')]
    public Collection $packages;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    public ?string $totalPrice = null;

    #[ORM\Column(length: 20)]
    public string $status = 'pending';

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->packages = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return 'Reservation #' . $this->id . ' - ' . $this->lane . ' (' . $this->startTime?->format('d/m/Y H:i') . ')';
    }
}
