<?php

namespace App\DataFixtures;

use App\Entity\Package;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PackageFixtures extends Fixture
{
    public const SNACK_BASIC = 'package-snack-basic';
    public const SNACK_LUXURY = 'package-snack-luxury';
    public const PARTY_CHILDREN = 'package-party-children';
    public const PARTY_BACHELOR = 'package-party-bachelor';

    public function load(ObjectManager $manager): void
    {
        $packages = [
            [
                'ref' => self::SNACK_BASIC,
                'type' => Package::TYPE_SNACK,
                'name' => 'Basic Snack Package',
                'description' => 'Selection of chips, nuts, and soft drinks for your group.',
                'price' => '15.00',
            ],
            [
                'ref' => self::SNACK_LUXURY,
                'type' => Package::TYPE_SNACK,
                'name' => 'Luxury Snack Package',
                'description' => 'Premium snack platter with nachos, chicken wings, mini burgers, and a choice of drinks.',
                'price' => '35.00',
            ],
            [
                'ref' => self::PARTY_CHILDREN,
                'type' => Package::TYPE_PARTY,
                'name' => "Children's Party",
                'description' => 'Includes 2 hours of bowling, party decorations, cake, and soft drinks for up to 10 children.',
                'price' => '75.00',
            ],
            [
                'ref' => self::PARTY_BACHELOR,
                'type' => Package::TYPE_PARTY,
                'name' => 'Bachelor Party',
                'description' => 'Includes 3 hours of bowling, a dedicated lane, snack platter, and a round of drinks.',
                'price' => '120.00',
            ],
        ];

        foreach ($packages as $data) {
            $package = new Package();
            $package->type = $data['type'];
            $package->name = $data['name'];
            $package->description = $data['description'];
            $package->price = $data['price'];

            $manager->persist($package);
            $this->addReference($data['ref'], $package, Package::class);
        }

        $manager->flush();
    }
}
