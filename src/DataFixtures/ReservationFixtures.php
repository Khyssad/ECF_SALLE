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
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPhoneNumber($faker->phoneNumber)
                ->setReservationDate(new \DateTimeImmutable('-1 year', 'now'))
                ->setReservationTime($faker->time('H:i'))
                ->setNumberOfGuests($faker->numberBetween(1, 10))
                ->setSpecialRequests($faker->sentence)
                ->setCreatedAt(new \DateTimeImmutable('-1 year', 'now'))
                ->setUpdatedAt(new \DateTimeImmutable('-1 year', 'now'));
        }

        $manager->flush();
    }
}