<?php

declare(strict_types=1);

namespace App\Message;

#[AsMessage('async')]
final class StoreViewedMessage
{
    public function __construct(public int $storeId) {}
}