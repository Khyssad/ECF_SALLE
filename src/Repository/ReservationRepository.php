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

    public function isRoomAvailable(int $roomId, \DateTimeInterface $startDate, \DateTimeInterface $endDate): bool
    {
        $qb = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.room = :roomId')
            ->andWhere('r.status != :cancelledStatus')
            ->andWhere(
                '(r.startDate < :endDate AND r.endDate > :startDate)'
            )
            ->setParameter('roomId', $roomId)
            ->setParameter('cancelledStatus', 'cancelled')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count == 0;
    }
}