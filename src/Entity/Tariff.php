<?php

namespace App\Entity;

use App\Repository\TariffRepository;
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

    #[ORM\Column(length: 20)]
    public ?string $dayRange = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    public ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    public ?\DateTimeInterface $endTime = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    public ?string $pricePerHour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->name . ' (€' . $this->pricePerHour . '/hr)';
    }
}
