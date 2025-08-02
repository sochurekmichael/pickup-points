<?php

declare(strict_types=1);

namespace App\Import\Resolver\Status;

use App\Enum\PickupPointStatus;

class BalikovnaStatusResolver implements PickupPointStatusResolverInterface
{
    public function resolve(string $status): PickupPointStatus
    {
        return PickupPointStatus::AVAILABLE;
    }
}
