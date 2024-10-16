<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReservationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create 50 reservations
        for ($i = 0; $i < 50; $i++) {
            $reservation = new Reservation();
            $reservation
                ->setStartDate(new \DateTimeImmutable('-30 days'))
                ->setEndDate(new \DateTimeImmutable('now'))
                ->setStatus($faker->randomElement([Reservation::STATUS_PENDING, Reservation::STATUS_CONFIRMED, Reservation::STATUS_CANCELLED]))
                ->setUsers($this->getReference('user_'.rand(1, 100)))
                ->setRoom($this->getReference('room_'.rand(1, 10)));

            $manager->persist($reservation);
        }

        $manager->flush();
    }
}