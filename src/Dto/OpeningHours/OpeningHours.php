<?php

declare(strict_types=1);

namespace App\Dto\OpeningHours;

readonly class OpeningHours
{
    /** @var DayOpeningHours[] $days */
    public function __construct(
        public array $days,
    ) {
    }

    public function toJson(): string
    {
        $days = [];

        foreach ($this->days as $day) {
            $days[$day->dayName] = array_map(
                fn(TimeInterval $timeInterval) => [
                    'from' => $timeInterval->from,
                    'to' => $timeInterval->to
                ],
                $day->intervals
            );
        }

        return json_encode($days, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    }
}
