<?php

namespace App\Repository;

use App\Entity\Room;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ReservationRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Reservation::class);
        $this->entityManager = $entityManager;
    }

    public function save(Reservation $entity, bool $flush = false): void
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->entityManager->remove($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
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

    public function findUpcomingReservations()
    {
        $fiveDaysFromNow = new \DateTime('+5 days');
        $now = new \DateTime();

        return $this->createQueryBuilder('r')
            ->andWhere('r.status = :status')
            ->andWhere('r.startDate > :now')
            ->andWhere('r.startDate <= :fiveDaysFromNow')
            ->setParameter('status', Reservation::STATUS_PRE_RESERVED)
            ->setParameter('now', $now)
            ->setParameter('fiveDaysFromNow', $fiveDaysFromNow)
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

    public function isRoomAvailable($room, \DateTimeInterface $start, \DateTimeInterface $end): bool
    {
        $overlappingReservations = $this->createQueryBuilder('r')
            ->andWhere('r.room = :room')
            ->andWhere('r.status != :cancelledStatus')
            ->andWhere('(r.startDate < :end AND r.endDate > :start)')
            ->setParameter('room', $room)
            ->setParameter('cancelledStatus', Reservation::STATUS_CANCELLED)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();

        return count($overlappingReservations) === 0;
    }


    public function findReservationsForRoom(Room $room, \DateTimeInterface $start, \DateTimeInterface $end)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.room = :room')
            ->andWhere('r.startDate < :end')
            ->andWhere('r.endDate > :start')
            ->setParameter('room', $room)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }

}