<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Store;
use App\Entity\Vote;
use App\Enum\VoteType;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/api/votes')]
final class VoteController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        private VoteRepository $voteRepo
    ) {}

    #[Route('', name: 'create_vote', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $storeId = $data['storeId'] ?? null;
        $type = $data['type'] ?? null;

        if (!$storeId || !in_array($type, ['like', 'dislike'])) {
            return $this->json(['error' => 'Invalid input'], Response::HTTP_BAD_REQUEST);
        }

        $store = $this->em->getRepository(Store::class)->find($storeId);
        $user = $this->security->getUser();

        if (null === $store || null === $user) {
            return $this->json(['error' => 'Store or user not found'], Response::HTTP_NOT_FOUND);
        }

        $existingVote = $this->voteRepo->findOneByUserAndStore($user, $store);

        if ($existingVote) {
            return $this->json(['error' => 'Vote already exists'], Response::HTTP_CONFLICT);
        }

        $vote = new Vote();
        $vote->setStore($store);
        $vote->setUser($user);
        $vote->setType(VoteType::from($type));

        $this->em->persist($vote);
        $this->em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{storeId}', name: 'update_vote', methods: ['PUT'])]
    public function update(int $storeId, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $type = $data['type'] ?? null;

        if (!in_array($type, ['like', 'dislike'])) {
            return $this->json(['error' => 'Invalid vote type'], Response::HTTP_BAD_REQUEST);
        }

        $store = $this->em->getRepository(Store::class)->find($storeId);
        $user = $this->security->getUser();

        if (null === $store || null === $user) {
            return $this->json(['error' => 'Store or user not found'], Response::HTTP_NOT_FOUND);
        }

        $vote = $this->voteRepo->findOneByUserAndStore($user, $store);

        if (!$vote) {
            return $this->json(['error' => 'Vote not found'], Response::HTTP_NOT_FOUND);
        }

        $vote->setType(VoteType::from($type));
        $this->em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{storeId}', name: 'delete_vote', methods: ['DELETE'])]
    public function delete(int $storeId): JsonResponse
    {
        $store = $this->em->getRepository(Store::class)->find($storeId);
        $user = $this->security->getUser();

        if (null === $store || null === $user) {
            return $this->json(['error' => 'Store or user not found'], Response::HTTP_NOT_FOUND);
        }

        $vote = $this->voteRepo->findOneByUserAndStore($user, $store);

        if (!$vote) {
            return $this->json(['error' => 'Vote not found'], Response::HTTP_NOT_FOUND);
        }

        $this->em->remove($vote);
        $this->em->flush();

        return $this->json(['success' => true]);
    }
}
