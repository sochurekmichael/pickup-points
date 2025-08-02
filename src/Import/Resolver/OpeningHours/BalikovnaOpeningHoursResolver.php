<?php

declare(strict_types=1);

namespace App\Import\Resolver\OpeningHours;

use App\Dto\OpeningHours\DayOpeningHours;
use App\Dto\OpeningHours\OpeningHours;
use App\Dto\OpeningHours\TimeInterval;
use InvalidArgumentException;
use SimpleXMLElement;

class BalikovnaOpeningHoursResolver implements PickupPointOpeningHoursResolverInterface
{
    public function resolve(mixed $openingHours): OpeningHours
    {
        if (!$openingHours instanceof SimpleXMLElement) {
            throw new InvalidArgumentException('Expected SimpleXMLElement instance');
        }

        foreach ($openingHours->den as $day) {
            $intervals = [];

            foreach ($day->od_do as $interval) {
                $intervals[] = new TimeInterval(
                    (string)$interval->od,
                    (string)$interval->do,
                );
            }

            $days[] = new DayOpeningHours(
                (string)$day['name'],
                $intervals,
            );
        }

        return new OpeningHours($days);
    }
}
