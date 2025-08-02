<?php

declare(strict_types=1);

namespace App\Dto\OpeningHours;

readonly class DayOpeningHours
{
    /**
     * @param TimeInterval[] $intervals
     */
    public function __construct(
        public string $dayName,
        public array $intervals
    ) {
    }
}
