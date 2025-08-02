<?php

declare(strict_types=1);

namespace App\Dto\OpeningHours;

readonly class TimeInterval
{
    public function __construct(
        public string $from,
        public string $to
    ) {
    }
}
