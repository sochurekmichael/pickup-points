<?php

declare(strict_types=1);

namespace App\Import;

use App\Dto\OpeningHours\OpeningHours;
use App\Enum\PickupPointStatus;
use App\Enum\PickupPointType;
use App\Import\Resolver\ExternalIdResolver;
use App\Import\Resolver\OpeningHours\PickupPointOpeningHoursResolverInterface;
use App\Import\Resolver\Status\PickupPointStatusResolverInterface;
use App\Import\Resolver\Type\PickupPointTypeResolverInterface;

abstract class AbstractPickupPointImporter implements ImporterInterface
{
    public function __construct(
        private readonly ExternalIdResolver $externalIdResolver,
        private readonly PickupPointTypeResolverInterface $pickupPointTypeResolver,
        private readonly PickupPointStatusResolverInterface $pickupPointStatusResolver,
        private readonly PickupPointOpeningHoursResolverInterface $pickupPointOpeningHoursResolver,
    ) {
    }

    protected function resolveExternalId(string $zipCode, string $address): string
    {
        return $this->externalIdResolver->resolveExternalId($zipCode, $address);
    }

    protected function resolveType(string $type): PickupPointType
    {
        return $this->pickupPointTypeResolver->resolve(trim($type));
    }

    protected function resolveStatus(string $status): PickupPointStatus
    {
        return $this->pickupPointStatusResolver->resolve(trim($status));
    }

    protected function resolverOpeningHours(mixed $openingHours): ?OpeningHours
    {
        return $openingHours
            ? $this->pickupPointOpeningHoursResolver->resolve($openingHours)
            : null;
    }
}
