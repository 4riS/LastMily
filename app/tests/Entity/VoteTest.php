<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Store;
use App\Entity\User;
use App\Entity\Vote;
use App\Enum\VoteType;
use PHPUnit\Framework\TestCase;

final class VoteTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $vote = new Vote();

        $user = $this->createMock(User::class);
        $store = $this->createMock(Store::class);
        $type = VoteType::Like;

        $vote->setUser($user);
        $vote->setStore($store);
        $vote->setType($type);

        $this->assertSame($user, $vote->getUser());
        $this->assertSame($store, $vote->getStore());
        $this->assertSame($type, $vote->getType());
    }
}
