<?php

declare(strict_types=1);

namespace App\Import;

use App\Dto\PickupPoint;
use App\Enum\Carrier;
use Generator;

interface ImporterInterface
{
    /**
     * @return Generator<PickupPoint>
     */
    public function fetchPickupPoints(): Generator;

    public function getCarrier(): Carrier;
}
