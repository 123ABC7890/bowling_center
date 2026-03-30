<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $reservations = [
            // Weekday reservation, no packages
            [
                'user' => UserFixtures::USER_CUSTOMER,
                'lane' => LaneFixtures::LANE_REFERENCE_PREFIX . '1',
                'tariff' => TariffFixtures::TARIFF_MONTHU,
                'appliedRate' => '24.00',
                'start' => 'next Monday 15:00',
                'end' => 'next Monday 17:00',
                'adults' => 4,
                'children' => 0,
                'snack' => null,
                'party' => null,
                'totalPrice' => '48.00',
                'status' => 'confirmed',
            ],
            // Weekend afternoon with basic snack package
            [
                'user' => UserFixtures::USER_CUSTOMER,
                'lane' => LaneFixtures::LANE_REFERENCE_PREFIX . '3',
                'tariff' => TariffFixtures::TARIFF_FRISUN_AFTERNOON,
                'appliedRate' => '28.00',
                'start' => 'next Saturday 14:00',
                'end' => 'next Saturday 16:00',
                'adults' => 6,
                'children' => 0,
                'snack' => PackageFixtures::SNACK_BASIC,
                'party' => null,
                'totalPrice' => '71.00',
                'status' => 'confirmed',
            ],
            // Weekend evening with luxury snack
            [
                'user' => UserFixtures::USER_CUSTOMER,
                'lane' => LaneFixtures::LANE_REFERENCE_PREFIX . '5',
                'tariff' => TariffFixtures::TARIFF_FRISUN_EVENING,
                'appliedRate' => '33.50',
                'start' => 'next Friday 19:00',
                'end' => 'next Friday 21:00',
                'adults' => 8,
                'children' => 0,
                'snack' => PackageFixtures::SNACK_LUXURY,
                'party' => null,
                'totalPrice' => '102.00',
                'status' => 'pending',
            ],
            // Children's party on bumper lane
            [
                'user' => UserFixtures::USER_CUSTOMER,
                'lane' => LaneFixtures::LANE_REFERENCE_PREFIX . '7',
                'tariff' => TariffFixtures::TARIFF_FRISUN_AFTERNOON,
                'appliedRate' => '28.00',
                'start' => 'next Saturday 15:00',
                'end' => 'next Saturday 17:00',
                'adults' => 3,
                'children' => 4,
                'snack' => PackageFixtures::SNACK_BASIC,
                'party' => PackageFixtures::PARTY_CHILDREN,
                'totalPrice' => '146.00',
                'status' => 'confirmed',
            ],
            // Bachelor party
            [
                'user' => UserFixtures::USER_CUSTOMER,
                'lane' => LaneFixtures::LANE_REFERENCE_PREFIX . '2',
                'tariff' => TariffFixtures::TARIFF_FRISUN_EVENING,
                'appliedRate' => '33.50',
                'start' => 'next Friday 20:00',
                'end' => 'next Friday 23:00',
                'adults' => 8,
                'children' => 0,
                'snack' => PackageFixtures::SNACK_LUXURY,
                'party' => PackageFixtures::PARTY_BACHELOR,
                'totalPrice' => '255.50',
                'status' => 'confirmed',
            ],
            // Cancelled reservation
            [
                'user' => UserFixtures::USER_CUSTOMER,
                'lane' => LaneFixtures::LANE_REFERENCE_PREFIX . '4',
                'tariff' => TariffFixtures::TARIFF_MONTHU,
                'appliedRate' => '24.00',
                'start' => 'next Wednesday 16:00',
                'end' => 'next Wednesday 18:00',
                'adults' => 2,
                'children' => 0,
                'snack' => null,
                'party' => null,
                'totalPrice' => '48.00',
                'status' => 'cancelled',
            ],
        ];

        foreach ($reservations as $data) {
            $reservation = new Reservation();
            $reservation->user = $this->getReference($data['user'], \App\Entity\User::class);
            $reservation->lane = $this->getReference($data['lane'], \App\Entity\Lane::class);
            $reservation->tariff = $this->getReference($data['tariff'], \App\Entity\Tariff::class);
            $reservation->appliedRate = $data['appliedRate'];
            $reservation->startTime = new \DateTime($data['start']);
            $reservation->endTime = new \DateTime($data['end']);
            $reservation->numberOfAdults = $data['adults'];
            $reservation->numberOfChildren = $data['children'];
            $reservation->snackPackage = $data['snack'] ? $this->getReference($data['snack'], \App\Entity\Package::class) : null;
            $reservation->partyPackage = $data['party'] ? $this->getReference($data['party'], \App\Entity\Package::class) : null;
            $reservation->totalPrice = $data['totalPrice'];
            $reservation->status = $data['status'];

            $manager->persist($reservation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            LaneFixtures::class,
            TariffFixtures::class,
            PackageFixtures::class,
        ];
    }
}
