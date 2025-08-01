<?php

declare(strict_types=1);

namespace App\Enum;

enum PickupPointStatus: string
{
    case AVAILABLE = 'available';
    case TEMPORARILY_UNAVAILABLE = 'temporarily_unavailable';
    case CLOSED = 'closed';
    case TERMINATED = 'terminated';
}
