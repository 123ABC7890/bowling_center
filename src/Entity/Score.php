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

    public function addRound(array $playerScores): self
    {
        $this->value[] = $playerScores;

        return $this;
    }

    public function setPlayerScore(int $round, string $playerName, int $score): self
    {
        if (!isset($this->value[$round])) {
            throw new \OutOfRangeException(sprintf('Round %d does not exist. Add it first with addRound().', $round));
        }

        $this->value[$round][$playerName] = $score;

        return $this;
    }

    public function getPlayerScore(int $round, string $playerName): ?int
    {
        return $this->value[$round][$playerName] ?? null;
    }

    public function getRound(int $round): ?array
    {
        return $this->value[$round] ?? null;
    }

    public function getRoundCount(): int
    {
        return count($this->value);
    }

    public function getPlayerNames(): array
    {
        if (empty($this->value)) {
            return [];
        }

        return array_keys($this->value[0]);
    }

    public function getPlayerTotal(string $playerName): int
    {
        $total = 0;

        foreach ($this->value as $round) {
            $total += $round[$playerName] ?? 0;
        }

        return $total;
    }
}
