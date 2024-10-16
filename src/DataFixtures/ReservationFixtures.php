<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReservationFixtures extends Fixture
{
    public const REFERENCE_NAME = 'reservation';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create 50 reservations
        for ($i = 0; $i < 50; $i++) {
            $reservation = new Reservation();
            $reservation
                ->setStartDate(new \DateTimeImmutable('-6 months'))
                ->setEndDate(new \DateTimeImmutable('-5 months'))
                ->setStatus('CONFIRMED')
                ->setUsers($this->getReference('user_reference')) // Updated reference
                ->setRoom($this->getReference('room_reference')); // Updated reference

            $manager->persist($reservation);
        }

        $this->addReference(self::REFERENCE_NAME, $reservation);

        $manager->flush();
    }
}