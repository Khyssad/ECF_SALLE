<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

class RoomRepository extends ServiceEntityRepository
{
    private $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Room::class);
        $this->logger = $logger;
    }

    public function searchRooms(array $criteria)
    {
        $this->logger->info('Starting room search', ['criteria' => $criteria]);

        $qb = $this->createQueryBuilder('r');

        if (!empty($criteria['name'])) {
            $qb->andWhere('r.name LIKE :name')
                ->setParameter('name', '%' . $criteria['name'] . '%');
            $this->logger->debug('Added name condition', ['name' => $criteria['name']]);
        }

        if (!empty($criteria['capacity'])) {
            $qb->andWhere('r.capacity >= :capacity')
                ->setParameter('capacity', $criteria['capacity']);
            $this->logger->debug('Added capacity condition', ['capacity' => $criteria['capacity']]);
        }

        if (!empty($criteria['equipments'])) {
            foreach ($criteria['equipments'] as $key => $equipment) {
                $qb->andWhere('r.equipments LIKE :equipment' . $key)
                   ->setParameter('equipment' . $key, '%' . $equipment . '%');
                $this->logger->debug('Added equipment condition', ['equipment' => $equipment]);
            }
        }

        if (!empty($criteria['ergonomics'])) {
            foreach ($criteria['ergonomics'] as $key => $ergonomic) {
                $qb->andWhere('r.ergonomics LIKE :ergonomic' . $key)
                   ->setParameter('ergonomic' . $key, '%' . $ergonomic . '%');
                $this->logger->debug('Added ergonomic condition', ['ergonomic' => $ergonomic]);
            }
        }

        $query = $qb->getQuery();
        $this->logger->debug('Final DQL', ['dql' => $query->getDQL()]);
        $this->logger->debug('Query parameters', ['parameters' => $query->getParameters()->toArray()]);

        $result = $query->getResult();
        $this->logger->info('Search completed', ['results_count' => count($result)]);

        return $result;
    }
}