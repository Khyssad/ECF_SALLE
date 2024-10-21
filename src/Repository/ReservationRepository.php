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

    public function findPendingReservations()
    {
        $fiveDaysFromNow = new \DateTime('+5 days');

        return $this->createQueryBuilder('r')
            ->andWhere('r.status = :status')
            ->andWhere('r.startDate <= :fiveDaysFromNow')
            ->setParameter('status', Reservation::STATUS_PRE_RESERVED)
            ->setParameter('fiveDaysFromNow', $fiveDaysFromNow)
            ->getQuery()
            ->getResult();
    }

    public function findOldPendingReservations(\DateTimeImmutable $threshold)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.status = :status')
            ->andWhere('r.startDate <= :threshold')
            ->setParameter('status', Reservation::STATUS_PRE_RESERVED)
            ->setParameter('threshold', $threshold)
            ->getQuery()
            ->getResult();
    }

    public function countByStatus(string $status): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }
}