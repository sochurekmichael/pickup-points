<?php

declare(strict_types=1);

namespace App\Import\Resolver\Type;

use App\Enum\PickupPointType;

interface PickupPointTypeResolverInterface
{
    public function resolve(string $type): PickupPointType;
}
