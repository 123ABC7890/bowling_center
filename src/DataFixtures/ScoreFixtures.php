<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use App\Entity\Score;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ScoreFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $score = new Score();
        $score->reservation = $this->getReference(ReservationFixtures::RESERVATION_REFERENCE_PREFIX . '0', Reservation::class);
        $score->addRound(['John' => 50, 'Alice' => 40, 'Joe' => 100]);
        $score->addRound(['John' => 45, 'Alice' => 40, 'Joe' => 110]);

        $manager->persist($score);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ReservationFixtures::class,
        ];
    }
}
