<?php

declare(strict_types=1);

namespace App\Enum;

enum PickupPointType: string
{
    case BOX = 'box';
    case POINT = 'point';
}
