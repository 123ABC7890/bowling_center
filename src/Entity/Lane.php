<?php

namespace App\Entity;

use App\Repository\LaneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LaneRepository::class)]
class Lane
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    public ?int $number = null;

    #[ORM\Column]
    public bool $hasBumpers = false;

    #[ORM\Column]
    public int $maxAdults = 8;

    #[ORM\Column]
    public int $maxChildrenWithAdults = 4;

    #[ORM\Column]
    public int $maxAdultsWithChildren = 6;

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
