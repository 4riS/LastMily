<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Store;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class StoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Store::class);
    }

    public function getVotesStats(Store $store): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT 
                SUM(CASE WHEN v.type = "like" THEN 1 ELSE 0 END) AS likes,
                SUM(CASE WHEN v.type = "dislike" THEN 1 ELSE 0 END) AS dislikes
            FROM vote v
            WHERE v.store_id = :storeId
        ';

        $stmt = $conn->prepare($sql);
        $queryResult = $stmt->executeQuery(['storeId' => $store->getId()]);
        return $queryResult->fetchAssociative();
    }

    public function findWithFilters(?string $search, string $sort, string $direction): array
    {
        $qb = $this->createQueryBuilder('s');

        if ($search) {
            $qb->andWhere('s.title LIKE :search OR s.description LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if (in_array($sort, ['title', 'created_at'])) {
            $qb->orderBy('s.' . $sort, $direction);
        } else {
            $qb->orderBy('s.created_at', 'DESC');
        }

        return $qb->getQuery()->getResult();
    }
}
