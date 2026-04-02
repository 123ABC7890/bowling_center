<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Reservation::class, inversedBy: 'score')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Reservation $reservation = null;

    #[ORM\Column(type: 'json')]
    public array $value = [];

    public function getId(): ?int
    {
        return $this->id;
    }
}
