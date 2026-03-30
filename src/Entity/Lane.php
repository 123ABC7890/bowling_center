<?php

namespace App\Entity;

use App\Repository\LaneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LaneRepository::class)]
class Lane
{
    public const MAX_ADULTS = 8;
    public const MAX_ADULTS_WITH_CHILDREN = 6;
    public const MAX_CHILDREN_WITH_ADULTS = 4;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    public ?int $number = null;

    #[ORM\Column]
    public bool $hasBumpers = false;

    /** @var Collection<int, Reservation> */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'lane')]
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
        return 'Lane ' . $this->number . ($this->hasBumpers ? ' (bumpers)' : '');
    }
}
