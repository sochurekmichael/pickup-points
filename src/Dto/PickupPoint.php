<?php

declare(strict_types=1);

namespace App\Dto;

use App\Dto\OpeningHours\OpeningHours;
use App\Enum\Carrier;
use App\Enum\Country;
use App\Enum\PickupPointStatus;
use App\Enum\PickupPointType;
use DateTimeImmutable;

readonly class PickupPoint
{
    public function __construct(
        public string $externalId,
        public Carrier $carrier,
        public PickupPointType $type,
        public PickupPointStatus $status,
        public string $city,
        public string $name,
        public string $address,
        public string $zipCode,
        public Country $country,
        public float $latitude,
        public float $longitude,
        public DateTimeImmutable $created,
        public bool $isCurrent = false,
        public ?OpeningHours $openingHours = null,
        public ?int $id = null, // auto-incremented
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function __toArray(): array
    {
        return [
            'externalId' => $this->externalId,
            'carrier' => $this->carrier->value,
            'type' => $this->type->value,
            'status' => $this->status->value,
            'city' => $this->city,
            'name' => $this->name,
            'address' => $this->address,
            'zipCode' => $this->zipCode,
            'country' => $this->country->value,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'openingHours' => $this->openingHours?->toJson(),
            'created' => $this->created->format('Y-m-d H:i:s'),
            'isCurrent' => $this->isCurrent,
        ];
    }
}
