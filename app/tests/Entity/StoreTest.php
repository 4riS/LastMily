<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Store;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class StoreTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $store = new Store();

        $title = 'My Store';
        $description = 'A little shop';
        $createdAt = new DateTimeImmutable();
        $views = 42;
        $votes = new ArrayCollection();

        $store->setTitle($title);
        $store->setDescription($description);
        $store->setCreatedAt($createdAt);
        $store->setViews($views);
        $store->setVotes($votes);

        $this->assertSame($title, $store->getTitle());
        $this->assertSame($description, $store->getDescription());
        $this->assertSame($createdAt, $store->getCreatedAt());
        $this->assertSame($views, $store->getViews());
        $this->assertSame($votes, $store->getVotes());
    }
}