<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReservationFixtures extends Fixture
{
    public const REFERENCE_NAME = 'reservation_reference';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create 50 reservations
        for ($i = 0; $i < 50; $i++) {
            $reservation = new Reservation();
            $startDate = new \DateTimeImmutable('-3 months');
            $endDate = $startDate->modify('+'. $faker->numberBetween(1, 3).'days');
            $reservation
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setStatus($faker->randomElement([Reservation::STATUS_PENDING, Reservation::STATUS_CONFIRMED, Reservation::STATUS_CANCELLED]))
                ->setUsers($this->getReference('user_reference'))
                ->setRoom($this->getReference('room_reference'));

            $manager->persist($reservation);
        }

        $manager->flush();
    }
}