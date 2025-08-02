<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\PickupPoint;
use App\Enum\Carrier;
use App\Enum\Country;
use App\Enum\PickupPointStatus;
use PDO;

class PickupPointRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @param PickupPoint[] $pickupPoints
     * @return void
     */
    public function upsert(array $pickupPoints): void
    {
        if ($pickupPoints === []) {
            return;
        }

        $columns = [
            'externalId',
            'carrier',
            'type',
            'status',
            'city',
            'name',
            'address',
            'zipCode',
            'country',
            'latitude',
            'longitude',
            'openingHours',
            'created',
            'isCurrent'
        ];

        $updateColumns = [
            'type',
            'status',
            'city',
            'name',
            'address',
            'zipCode',
            'latitude',
            'longitude',
            'openingHours',
            'isCurrent',
        ];

        $placeholders = [];
        $values = [];

        $expectedColumnCount = count($columns);
        $placeholderRow = '(' . rtrim(str_repeat('?, ', $expectedColumnCount), ', ') . ')';

        foreach ($pickupPoints as $pickupPoint) {
            $row = array_values($pickupPoint->__toArray());

            if (count($row) !== $expectedColumnCount) {
                throw RepositoryException::byCountColumnsNotMatch(self::class);
            }

            $placeholders[] = $placeholderRow;
            $values = array_merge($values, $row);
        }

        $sql = sprintf(
            'INSERT INTO pickup_points (%s) VALUES %s ON DUPLICATE KEY UPDATE %s',
            implode(', ', $columns),
            implode(', ', $placeholders),
            implode(', ', array_map(fn($column) => "$column = VALUES($column)", $updateColumns))
        );

        $statement = $this->pdo->prepare($sql);
        $statement->execute($values);
    }

    public function markAllAsOutdated(Carrier $carrier, Country $country): void
    {
        $statement = $this->pdo->prepare("
            UPDATE pickup_points
                SET isCurrent = 0
            WHERE carrier = :carrier AND country = :country
        ");

        $statement->execute([
            ':carrier' => $carrier->value,
            ':country' => $country->value,
        ]);
    }

    public function markNotCurrentAsTemporarilyUnavailable(Carrier $carrier, Country $country): void
    {
        $statement = $this->pdo->prepare("
            UPDATE pickup_points
                SET status = :status
            WHERE carrier = :carrier AND country = :country AND isCurrent = 0
        ");

        $statement->execute([
            ':status' => PickupPointStatus::TEMPORARILY_UNAVAILABLE->value,
            ':carrier' => $carrier->value,
            ':country' => $country->value,
        ]);
    }
}
