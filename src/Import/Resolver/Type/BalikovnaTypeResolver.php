<?php

declare(strict_types=1);

namespace App\Import\Resolver\Type;

use App\Enum\PickupPointType;

class BalikovnaTypeResolver implements PickupPointTypeResolverInterface
{
    public function resolve(string $type): PickupPointType
    {
        return match ($type) {
            'balikovna', 'balíkovna partner', 'depo', 'pošta' => PickupPointType::POINT,
            'balíkovna-BOX' => PickupPointType::BOX,
            default => PickupPointType::POINT,
        };
    }
}
