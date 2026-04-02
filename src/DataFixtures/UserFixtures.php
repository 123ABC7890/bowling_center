<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_ADMIN = 'user-admin';
    public const USER_EMPLOYEE = 'user-employee';
    public const USER_CUSTOMER = 'user-customer';

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private string $adminEmail,
        private string $adminPassword,
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Admin - credentials from .env
        $admin = new User();
        $admin->email = $this->adminEmail;
        $admin->roles = ['ROLE_ADMIN'];
        $admin->firstName = 'Jan';
        $admin->lastName = 'de Vries';
        $admin->phone = '+31 6 12345678';
        $admin->password = $this->passwordHasher->hashPassword($admin, $this->adminPassword);
        $manager->persist($admin);
        $this->addReference(self::USER_ADMIN, $admin, User::class);

        // Employee
        $employee = new User();
        $employee->email = 'employee@bowlingcenter.nl';
        $employee->roles = ['ROLE_EMPLOYEE'];
        $employee->firstName = 'Sophie';
        $employee->lastName = 'Bakker';
        $employee->phone = '+31 6 23456789';
        $employee->password = $this->passwordHasher->hashPassword($employee, $this->adminPassword);
        $manager->persist($employee);
        $this->addReference(self::USER_EMPLOYEE, $employee, User::class);

        // Customer
        $customer = new User();
        $customer->email = 'pieter@example.com';
        $customer->roles = [];
        $customer->firstName = 'Pieter';
        $customer->lastName = 'Jansen';
        $customer->phone = '+31 6 34567890';
        $customer->password = $this->passwordHasher->hashPassword($customer, $this->adminPassword);
        $manager->persist($customer);
        $this->addReference(self::USER_CUSTOMER, $customer, User::class);

        $manager->flush();
    }
}
