<?php

namespace App\DataFixtures;

use App\Entity\Lane;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LaneFixtures extends Fixture
{
    public const LANE_REFERENCE_PREFIX = 'lane-';

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 8; $i++) {
            $lane = new Lane();
            $lane->number = $i;
            $lane->hasBumpers = $i >= 7; // Lanes 7 and 8 have bumpers

            $manager->persist($lane);
            $this->addReference(self::LANE_REFERENCE_PREFIX . $i, $lane, Lane::class);
        }

        $manager->flush();
    }
}
