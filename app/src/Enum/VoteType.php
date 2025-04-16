<?php

declare(strict_types=1);

namespace App\Enum;

enum VoteType: string {
    case Like = 'like';
    case Dislike = 'dislike';
}
