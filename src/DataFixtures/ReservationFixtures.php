<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\RoomFixtures;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Utiliser la référence existante
        $adminUser = $this->getReference(UserFixtures::USER_REFERENCE);

        // Create 50 reservations for the admin user
        for ($i = 0; $i < 50; $i++) {
            $reservation = new Reservation();
            $startDate = new \DateTimeImmutable('-1 months');
            $reservation
                ->setStartDate($startDate)
                ->setEndDate($startDate->modify('+1 week'))
                ->setStatus('pending')
                ->setUsers($adminUser)
                ->setRoom($this->getReference(RoomFixtures::ROOM_REFERENCE));
            $manager->persist($reservation);
        }

        // Create 50 reservations for other users
        $otherUsers = $manager->getRepository(User::class)->findAll();
        foreach ($otherUsers as $user) {
            for ($i = 0; $i < 5; $i++) {
                $reservation = new Reservation();
                $startDate = new \DateTimeImmutable('-1 months');
                $reservation
                    ->setStartDate($startDate)
                    ->setEndDate($startDate->modify('+1 week'))
                    ->setStatus('pending')
                    ->setUsers($user)
                    ->setRoom($this->getReference(RoomFixtures::ROOM_REFERENCE));
                $manager->persist($reservation);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            RoomFixtures::class,
        ];
    }
}