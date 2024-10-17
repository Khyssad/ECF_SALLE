<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RoomFixtures extends Fixture
{
    public const ROOM_REFERENCE = 'room_reference';
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create 50 rooms
        for ($i = 0; $i < 50; $i++) {
            $room = new Room();
            $room
                ->setName($faker->company)
                ->setCapacity(20)
                ->setEquipments(['projector', 'television', 'sound system'])
                ->setErgonomics(['comfortable', 'quiet', 'efficient']);
            $manager->persist($room);
        }

        $manager->flush();

        // Save room reference for later use
        $this->setReference(self::ROOM_REFERENCE, $room);
    }
}