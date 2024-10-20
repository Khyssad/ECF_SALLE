<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function searchReservations(array $criteria)
    {
        $qb = $this->createQueryBuilder('r');

        if (!empty($criteria['startDate'])) {
            $qb->andWhere('r.startDate >= :startDate')
               ->setParameter('startDate', $criteria['startDate']);
        }

        if (!empty($criteria['endDate'])) {
            $qb->andWhere('r.endDate <= :endDate')
               ->setParameter('endDate', $criteria['endDate']);
        }

        if (!empty($criteria['status'])) {
            $qb->andWhere('r.status = :status')
               ->setParameter('status', $criteria['status']);
        }

        return $qb->getQuery()->getResult();
    }

    public function findPendingReservations()
    {
        $fiveDaysFromNow = new \DateTime('+5 days');

        return $this->createQueryBuilder('r')
            ->andWhere('r.status = :status')
            ->andWhere('r.startDate <= :fiveDaysFromNow')
            ->setParameter('status', Reservation::STATUS_PENDING)
            ->setParameter('fiveDaysFromNow', $fiveDaysFromNow)
            ->getQuery()
            ->getResult();
    }
}