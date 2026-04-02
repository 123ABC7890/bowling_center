<?php

namespace App\DataFixtures;

use App\Entity\DayRange;
use App\Entity\Tariff;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TariffFixtures extends Fixture
{
    public const TARIFF_MONTHU = 'tariff-monthu';
    public const TARIFF_FRISUN_AFTERNOON = 'tariff-frisun-afternoon';
    public const TARIFF_FRISUN_EVENING = 'tariff-frisun-evening';
    public const TARIFF_MAGIC_BOWLING = 'tariff-magic-bowling';

    public function load(ObjectManager $manager): void
    {
        $tariffs = [
            [
                'ref' => self::TARIFF_MONTHU,
                'name' => 'Mon-Thu',
                'dayRange' => DayRange::MonThu,
                'start' => '14:00',
                'end' => '22:00',
                'price' => '24.00',
            ],
            [
                'ref' => self::TARIFF_FRISUN_AFTERNOON,
                'name' => 'Fri-Sun Afternoon',
                'dayRange' => DayRange::FriSun,
                'start' => '14:00',
                'end' => '18:00',
                'price' => '28.00',
            ],
            [
                'ref' => self::TARIFF_FRISUN_EVENING,
                'name' => 'Fri-Sun Evening',
                'dayRange' => DayRange::FriSun,
                'start' => '18:00',
                'end' => '00:00',
                'price' => '33.50',
            ],
            [
                'ref' => self::TARIFF_MAGIC_BOWLING,
                'name' => 'Magic Bowling',
                'dayRange' => DayRange::FriSun,
                'start' => '22:00',
                'end' => '00:00',
                'price' => '38.00',
            ],
        ];

        foreach ($tariffs as $data) {
            $tariff = new Tariff();
            $tariff->name = $data['name'];
            $tariff->dayRange = $data['dayRange'];
            $tariff->startTime = new \DateTime($data['start']);
            $tariff->endTime = new \DateTime($data['end']);
            $tariff->pricePerHour = $data['price'];

            $manager->persist($tariff);
            $this->addReference($data['ref'], $tariff, Tariff::class);
        }

        $manager->flush();
    }
}
