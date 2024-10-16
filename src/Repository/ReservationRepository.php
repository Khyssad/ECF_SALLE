<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Room;
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
            ->setParameter('cancelledStatus', Reservation::STATUS_CANCELLED)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count == 0;
    }

    public function findPendingReservations(): array
    {
        $fiveDaysFromNow = new \DateTime('+5 days');

        return $this->createQueryBuilder('r')
            ->where('r.status = :status')
            ->andWhere('r.startDate <= :fiveDaysFromNow')
            ->setParameter('status', Reservation::STATUS_PENDING)
            ->setParameter('fiveDaysFromNow', $fiveDaysFromNow)
            ->orderBy('r.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findConflictingReservations(Room $room, \DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.room = :room')
            ->andWhere('r.status != :cancelledStatus')
            ->andWhere('(r.startDate < :endDate AND r.endDate > :startDate)')
            ->setParameter('room', $room)
            ->setParameter('cancelledStatus', Reservation::STATUS_CANCELLED)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }

    public function findOldPendingReservations(\DateTimeInterface $threshold): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.status = :status')
            ->andWhere('r.startDate < :threshold')
            ->setParameter('status', Reservation::STATUS_PENDING)
            ->setParameter('threshold', $threshold)
            ->orderBy('r.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}