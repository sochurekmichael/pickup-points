<?php

declare(strict_types=1);

namespace App\Import;

use App\Dto\PickupPoint;
use App\Enum\Carrier;
use App\Enum\Country;
use App\Import\Reader\Xml\XmlReaderProviderInterface;
use App\Import\Resolver\ExternalIdResolver;
use App\Import\Resolver\OpeningHours\BalikovnaOpeningHoursResolver;
use App\Import\Resolver\Status\BalikovnaStatusResolver;
use App\Import\Resolver\Type\BalikovnaTypeResolver;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use XMLReader;

class BalikovnaImporterTest extends TestCase
{
    public function testFetchPickupPointsParsesXmlCorrectly(): void
    {
        $importer = new BalikovnaImporter(
            $this->createReaderProviderMock(),
            new ExternalIdResolver(),
            new BalikovnaTypeResolver(),
            new BalikovnaStatusResolver(),
            new BalikovnaOpeningHoursResolver()
        );

        $pickupPoints = iterator_to_array($importer->fetchPickupPoints());
        /** @var PickupPoint[] $pickupPoints*/

        Assert::assertCount(2, $pickupPoints);

        Assert::assertSame('10000-cernokostelecka-2020-20-strasnice-10000-praha-10', $pickupPoints[0]->externalId);
        Assert::assertSame('Praha 10', $pickupPoints[0]->name);
        Assert::assertSame('Černokostelecká 2020/20, Strašnice, 10000, Praha 10', $pickupPoints[0]->address);
        Assert::assertSame('Praha', $pickupPoints[0]->city);
        Assert::assertSame('10000', $pickupPoints[0]->zipCode);
        Assert::assertSame(14.492777, $pickupPoints[0]->latitude);
        Assert::assertSame(50.076442, $pickupPoints[0]->longitude);
        Assert::assertSame(Carrier::BALIKOVNA, $pickupPoints[0]->carrier);
        Assert::assertSame(Country::CZ, $pickupPoints[0]->country);

        Assert::assertSame('10002-kodanska-485-63-vrsovice-10100-praha-10', $pickupPoints[1]->externalId);
        Assert::assertSame('Praha 10 SAZKA Žabka Kodaňská', $pickupPoints[1]->name);
        Assert::assertSame('Kodaňská 485/63, Vršovice, 10100, Praha 10', $pickupPoints[1]->address);
        Assert::assertSame('Praha', $pickupPoints[1]->city);
        Assert::assertSame('10002', $pickupPoints[1]->zipCode);
        Assert::assertSame(14.460133, $pickupPoints[1]->latitude);
        Assert::assertSame(50.070808, $pickupPoints[1]->longitude);
        Assert::assertSame(Carrier::BALIKOVNA, $pickupPoints[1]->carrier);
        Assert::assertSame(Country::CZ, $pickupPoints[1]->country);
    }


    public function testGetCarrier(): void
    {
        $importer = new BalikovnaImporter(
            $this->createMock(XmlReaderProviderInterface::class),
            new ExternalIdResolver(),
            new BalikovnaTypeResolver(),
            new BalikovnaStatusResolver(),
            new BalikovnaOpeningHoursResolver()
        );

        $carrier = $importer->getCarrier();

        Assert::assertSame(Carrier::BALIKOVNA, $carrier);
    }

    private function createReaderProviderMock(): XmlReaderProviderInterface&MockObject
    {
        $reader = new XMLReader();
        $reader->open(__DIR__ . '/__fixtures/balikovny.xml');

        $mock = $this->createMock(XmlReaderProviderInterface::class);
        $mock->expects($this->once())
            ->method('getReader')
            ->willReturn($reader);

        return $mock;
    }
}
