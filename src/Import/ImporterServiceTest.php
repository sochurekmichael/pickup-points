<?php

declare(strict_types=1);

namespace App\Import;

use App\Dto\PickupPoint;
use App\Enum\Carrier;
use App\Enum\Country;
use App\Repository\PickupPointRepository;
use Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

class ImporterServiceTest extends TestCase
{
    public function testImportRunsAllStepsForSingleImporter(): void
    {
        $pickupPoint1 = $this->createMock(PickupPoint::class);
        $pickupPoint2 = $this->createMock(PickupPoint::class);
        $importerMock = $this->createImporterMock([$pickupPoint1, $pickupPoint2]);
        $pickupPointRepositoryMock = $this->createPickupPointRepositoryMock([$pickupPoint1, $pickupPoint2]);

        $importerService = new ImporterService(
            [
                $importerMock,
            ],
            $pickupPointRepositoryMock
        );

        $importerService->import(
            $this->createMock(OutputInterface::class)
        );
    }

    /**
     * @param PickupPoint[] $pickupPoints
     */
    private function createImporterMock(array $pickupPoints): ImporterInterface&MockObject
    {
        $mock = $this->createMock(ImporterInterface::class);
        $mock->expects($this->exactly(3))
            ->method('getCarrier')
            ->willReturn(Carrier::BALIKOVNA);

        $mock->expects($this->once())
            ->method('fetchPickupPoints')
            ->willReturn($this->createPickupPointGenerator($pickupPoints));

        return $mock;
    }

    /**
     * @param PickupPoint[] $pickupPoints
     */
    private function createPickupPointGenerator(array $pickupPoints): Generator
    {
        foreach ($pickupPoints as $point) {
            yield $point;
        }
    }

    /**
     * @param PickupPoint[] $pickupPoints
     */
    private function createPickupPointRepositoryMock(array $pickupPoints): PickupPointRepository&MockObject
    {
        $mock = $this->createMock(PickupPointRepository::class);

        $mock->expects($this->once())
            ->method('markAllAsOutdated')
            ->with(Carrier::BALIKOVNA, Country::CZ);

        $mock->expects($this->once())
            ->method('upsert')
            ->with($pickupPoints);

        $mock->expects($this->once())
            ->method('markNotCurrentAsTemporarilyUnavailable')
            ->with(Carrier::BALIKOVNA, Country::CZ);

        return $mock;
    }
}
