<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\VoteType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'unique_vote', columns: ['user_id', 'store_id'])]
final class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Store::class, inversedBy: 'votes')]
    private Store $store;

    #[ORM\Column(type: 'string', enumType: VoteType::class)]
    private VoteType $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getStore(): Store
    {
        return $this->store;
    }

    public function setStore(Store $store): void
    {
        $this->store = $store;
    }

    public function getType(): VoteType
    {
        return $this->type;
    }
    public function setType(VoteType $type): void
    {
        $this->type = $type;
    }
}
