<?php

declare(strict_types=1);

namespace App\Import\Resolver\OpeningHours;

use App\Dto\OpeningHours\OpeningHours;

interface PickupPointOpeningHoursResolverInterface
{
    public function resolve(mixed $openingHours): OpeningHours;
}
