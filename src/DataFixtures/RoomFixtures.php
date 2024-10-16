<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RoomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create 100 rooms
        for ($i = 0; $i < 100; $i++) {
            $room = new Room();
            $room
                ->setName($faker->sentence(3))
                ->setCapacity($faker->numberBetween(1, 50))
                ->setEquipments($faker->randomElements(['Projector', 'Whiteboard', 'TV', 'Sound system', 'Air conditioning', 'Lights', 'Wifi', 'Coffee Systems', 'Smart home devices'], 5))
                ->setErgonomics($faker->randomElements(['Comfortable', 'Balanced', 'Relaxed', 'Active', 'Intimate'], 2));

            // Remove the addReservation call as ReservationFixtures::REFERENCE_NAME is undefined
            $manager->persist($room);
        }

        $manager->flush();
    }
}