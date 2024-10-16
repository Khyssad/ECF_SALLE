<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function searchRooms(array $criteria)
    {
        $qb = $this->createQueryBuilder('r');

        if (!empty($criteria['name'])) {
            $qb->andWhere('r.name LIKE :name')
                ->setParameter('name', '%' . $criteria['name'] . '%');
        }

        if (!empty($criteria['capacity'])) {
            $qb->andWhere('r.capacity >= :capacity')
                ->setParameter('capacity', $criteria['capacity']);
        }

        if (!empty($criteria['equipments'])) {
            $qb->andWhere('r.equipments LIKE :equipments')
                ->setParameter('equipments', '%' . implode('%', $criteria['equipments']) . '%');
        }

        if (!empty($criteria['ergonomics'])) {
            $qb->andWhere('r.ergonomics LIKE :ergonomics')
                ->setParameter('ergonomics', '%' . implode('%', $criteria['ergonomics']) . '%');
        }

        return $qb->getQuery()->getResult();
    }
}
