<?php

namespace App\Entity;

use App\Repository\TariffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TariffRepository::class)]
class Tariff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    public ?string $name = null;

    #[ORM\Column(length: 10, enumType: DayRange::class)]
    public ?DayRange $dayRange = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    public ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    public ?\DateTimeInterface $endTime = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    public ?string $pricePerHour = null;

    /** @var Collection<int, Reservation> */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'tariff')]
    public Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->name . ' (€' . $this->pricePerHour . '/hr)';
    }
}
