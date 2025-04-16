<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Store;
use App\Message\StoreViewedMessage;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

#[AsMessageHandler]
final class StoreViewedHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(StoreViewedMessage $message): void
    {
        $connection = $this->entityManager->getConnection();

        $connection->beginTransaction();

        try {
            $store = $this->entityManager->getRepository(Store::class)
                ->createQueryBuilder('s')
                ->where('s.id = :id')
                ->setParameter('id', $message->storeId)
                ->getQuery()
                ->setLockMode(LockMode::PESSIMISTIC_WRITE)
                ->getSingleResult();

            $store->setViews($store->getViews() + 1);
            $this->entityManager->flush();

            $connection->commit();
        } catch (Throwable $e) {
            $connection->rollBack();
            throw $e;
        }
    }
}
