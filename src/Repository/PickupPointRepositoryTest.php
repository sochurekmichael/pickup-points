<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\OpeningHours\OpeningHours;
use App\Dto\PickupPoint;
use App\Enum\Carrier;
use App\Enum\Country;
use App\Enum\PickupPointStatus;
use App\Enum\PickupPointType;
use DateTimeImmutable;
use PDO;
use PDOStatement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PickupPointRepositoryTest extends TestCase
{
    public function testUpsertWithValidPickupPoints(): void
    {
        $pickupPoint = $this->createPickupPoint();

        $statement = $this->createPdoStatementMock(
            array_values($pickupPoint->__toArray())
        );

        $repo = new PickupPointRepository(
            $this->createPdoForUpsertMock($statement)
        );
        $repo->upsert([$pickupPoint]);
    }

    public function testUpsertWithEmptyArrayDoesNothing(): void
    {
        $pickupPointRepository = new PickupPointRepository($this->createMock(PDO::class));
        $pickupPointRepository->upsert([]);

        $this->expectNotToPerformAssertions();
    }

    public function testUpsertWithInvalidColumnCountThrowsException(): void
    {
        $invalidPickupPoint = $this->createMock(PickupPoint::class);
        $invalidPickupPoint->expects($this->once())
            ->method('__toArray')
            ->willReturn(['tooFewColumns']);

        $this->expectException(RepositoryException::class);
        $this->expectExceptionMessage('The number of columns in the App\Repository\PickupPointRepository does not match');

        $pickupPointRepository = new PickupPointRepository($this->createMock(PDO::class));
        $pickupPointRepository->upsert([$invalidPickupPoint]);
    }

    public function testMarkAllAsOutdatedExecutesCorrectUpdate(): void
    {
        $statement = $this->createPdoStatementMock([
            ':carrier' => Carrier::BALIKOVNA->value,
            ':country' => Country::CZ->value
        ]);

        $pickupPointRepository = new PickupPointRepository(
            $this->createPdoForMarkAllAsOutdatedMock($statement)
        );
        $pickupPointRepository->markAllAsOutdated(Carrier::BALIKOVNA, Country::CZ);
    }

    public function testMarkNotCurrentAsTemporarilyUnavailableExecutesCorrectUpdate(): void
    {
        $statement = $this->createPdoStatementMock([
            ':status' => PickupPointStatus::TEMPORARILY_UNAVAILABLE->value,
            ':carrier' => Carrier::BALIKOVNA->value,
            ':country' => Country::CZ->value
        ]);

        $repo = new PickupPointRepository(
            $this->createPdoForMarkNotCurrentAsTemporarilyUnavailableMock($statement)
        );
        $repo->markNotCurrentAsTemporarilyUnavailable(Carrier::BALIKOVNA, Country::CZ);
    }

    /**
     * @param array<mixed> $withParameters
     */
    private function createPdoStatementMock(array $withParameters): PDOStatement&MockObject
    {
        $mock = $this->createMock(PDOStatement::class);
        $mock->expects($this->once())
            ->method('execute')
            ->with($withParameters);

        return $mock;
    }

    private function createPdoForUpsertMock(PDOStatement $statement): PDO&MockObject
    {
        $sql = "INSERT INTO pickup_points (externalId, carrier, type, status, city, name, address, zipCode, country, latitude, longitude, openingHours, created, isCurrent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE type = VALUES(type), status = VALUES(status), city = VALUES(city), name = VALUES(name), address = VALUES(address), zipCode = VALUES(zipCode), latitude = VALUES(latitude), longitude = VALUES(longitude), openingHours = VALUES(openingHours), isCurrent = VALUES(isCurrent)";

        $mock = $this->createMock(PDO::class);
        $mock->expects($this->once())
            ->method('prepare')
            ->with($sql)
            ->willReturn($statement);

        return $mock;
    }

    private function createPdoForMarkAllAsOutdatedMock(PDOStatement $statement): PDO&MockObject
    {
        $mock = $this->createMock(PDO::class);
        $mock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('UPDATE pickup_points
                SET iscurrent = 0
            WHERE carrier = :carrier AND country = :country'))
            ->willReturn($statement);

        return $mock;
    }

    private function createPdoForMarkNotCurrentAsTemporarilyUnavailableMock(PDOStatement $statement): PDO&MockObject
    {
        $mock = $this->createMock(PDO::class);
        $mock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('UPDATE pickup_points
                SET status = :status
            WHERE carrier = :carrier AND country = :country AND isCurrent = 0'))
            ->willReturn($statement);

        return $mock;
    }

    private function createPickupPoint(): PickupPoint
    {
        $pickupPoint = new PickupPoint(
            externalId: '11000-narodni-1',
            carrier: Carrier::BALIKOVNA,
            type: PickupPointType::POINT,
            status: PickupPointStatus::AVAILABLE,
            city: 'Praha',
            name: 'Balíkovna Praha 1',
            address: 'Národní 1',
            zipCode: '11000',
            country: Country::CZ,
            latitude: 50.087,
            longitude: 14.4208,
            created: new DateTimeImmutable('2025-08-03 12:00:00'),
            isCurrent: true,
            openingHours: $this->createMock(OpeningHours::class),
        );
        return $pickupPoint;
    }
}
