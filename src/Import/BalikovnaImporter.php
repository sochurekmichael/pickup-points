<?php

declare(strict_types=1);

namespace App\Import;

use App\Dto\PickupPoint;
use App\Enum\Carrier;
use App\Enum\Country;
use App\Import\Reader\Xml\XmlReaderProviderInterface;
use App\Import\Resolver\ExternalIdResolver;
use App\Import\Resolver\OpeningHours\PickupPointOpeningHoursResolverInterface;
use App\Import\Resolver\Status\PickupPointStatusResolverInterface;
use App\Import\Resolver\Type\PickupPointTypeResolverInterface;
use DateTimeImmutable;
use Generator;
use SimpleXMLElement;
use XMLReader;

class BalikovnaImporter extends AbstractPickupPointImporter
{
    private const FEED_URL = 'http://napostu.ceskaposta.cz/vystupy/balikovny.xml';
    private const NODE_IDENTIFIER = 'row' ;

    public function __construct(
        private readonly XmlReaderProviderInterface $xmlReaderProvider,
        ExternalIdResolver $externalIdResolver,
        PickupPointTypeResolverInterface $pickupPointTypeResolver,
        PickupPointStatusResolverInterface $pickupPointStatusResolver,
        PickupPointOpeningHoursResolverInterface $pickupPointOpeningHoursResolver
    ) {
        parent::__construct(
            $externalIdResolver,
            $pickupPointTypeResolver,
            $pickupPointStatusResolver,
            $pickupPointOpeningHoursResolver
        );
    }

    public function getCarrier(): Carrier
    {
        return Carrier::BALIKOVNA;
    }

    public function fetchPickupPoints(): Generator
    {
        $reader = $this->xmlReaderProvider->getReader(self::FEED_URL);

        while ($reader->read()) {
            if ($reader->nodeType === XMLReader::ELEMENT &&  $reader->name === self::NODE_IDENTIFIER) {
                $node = new SimpleXMLElement($reader->readOuterXML());

                yield $this->createPickupPoint($node);
            }
        }

        $reader->close();
    }

    private function createPickupPoint(SimpleXMLElement $node): PickupPoint
    {
        $zipCode = (string)$node->PSC;
        $address = (string)$node->ADRESA;

        return new PickupPoint(
            externalId: $this->resolveExternalId($zipCode, $address),
            carrier: $this->getCarrier(),
            type: $this->resolveType((string)$node->TYP),
            status: $this->resolveStatus((string)$node->STAV),
            city: (string)$node->OBEC,
            name: (string)$node->NAZEV,
            address: $address,
            zipCode: $zipCode,
            country: Country::CZ,
            latitude: (float)$node->SOUR_X_WGS84,
            longitude: (float)$node->SOUR_Y_WGS84,
            created: new DateTimeImmutable(),
            isCurrent: true,
            openingHours: $this->resolverOpeningHours($node->OTEV_DOBY),
        );
    }
}
