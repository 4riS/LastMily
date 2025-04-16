<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Store;
use App\Entity\User;
use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    public function findOneByUserAndStore(User $user, Store $store): ?Vote
    {
        return $this->findOneBy([
            'user' => $user,
            'store' => $store
        ]);
    }
}
