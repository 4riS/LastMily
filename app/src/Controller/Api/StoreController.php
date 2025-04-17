<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Store;
use App\Message\StoreViewedMessage;
use App\Repository\StoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/stores')]
final class StoreController extends AbstractController
{
    public function __construct(
        private readonly StoreRepository $storeRepo,
        private readonly MessageBusInterface $bus
    ) {}

    #[Route('', name: 'get_stores', methods: ['GET'])]
    public function getStores(Request $request): JsonResponse
    {
        $search = $request->query->get('search');
        $sort = $request->query->get('sort', 'id');
        $direction = strtolower($request->query->get('direction', 'desc'));

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        $stores = $this->storeRepo->findWithFilters($search, $sort, $direction);
        $result = [];

        foreach ($stores as $store) {
            $stats = $this->storeRepo->getVotesStats($store);

            $result[] = [
                'store_id' => $store->getId(),
                'title' => $store->getTitle(),
                'description' => $store->getDescription(),
                'created_at' => $store->getCreatedAt()->format('Y-m-d'),
                'likes_count' => (int) ($stats['likes'] ?? 0),
                'dislikes_count' => (int) ($stats['dislikes'] ?? 0),
                'views_count' => $store->getViewsCount(),
            ];

            $this->bus->dispatch(new StoreViewedMessage($store->getId()));
        }

        return new JsonResponse($result);
    }

    #[Route('/{id}', name: 'store_details', methods: ['GET'])]
    public function show(Store $store): JsonResponse
    {
        $this->bus->dispatch(new StoreViewedMessage($store->getId()));

        $stats = $this->storeRepo->getVotesStats($store);

        return $this->json([
            'id' => $store->getId(),
            'title' => $store->getTitle(),
            'description' => $store->getDescription(),
            'createdAt' => $store->getCreatedAt()->format('Y-m-d H:i:s'),
            'likes' => (int)($stats['likes'] ?? 0),
            'dislikes' => (int)($stats['dislikes'] ?? 0),
            'views' => $store->getViews()
        ]);
    }
}
