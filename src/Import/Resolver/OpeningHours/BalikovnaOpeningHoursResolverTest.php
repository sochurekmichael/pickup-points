<?php

declare(strict_types=1);

namespace App\Import\Resolver\OpeningHours;

use App\Dto\OpeningHours\DayOpeningHours;
use InvalidArgumentException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

class BalikovnaOpeningHoursResolverTest extends TestCase
{
    public function testResolveOpeningHoursFromXmlElement(): void
    {
        $xml = $this->createOpeningHoursXmlElement();

        $resolver = new BalikovnaOpeningHoursResolver();
        $openingHours = $resolver->resolve($xml);

        Assert::assertCount(2, $openingHours->days);

        $monday = $openingHours->days[0];
        assert($monday instanceof DayOpeningHours);
        Assert::assertSame('Pondělí', $monday->dayName);
        Assert::assertSame('00:00', $monday->intervals[0]->from);
        Assert::assertSame('23:59', $monday->intervals[0]->to);

        $tuesday = $openingHours->days[1];
        assert($tuesday instanceof DayOpeningHours);
        Assert::assertSame('Úterý', $tuesday->dayName);
        Assert::assertSame('12:00', $tuesday->intervals[0]->from);
        Assert::assertSame('23:59', $tuesday->intervals[0]->to);
    }

    public function testInvalidElementThrowException(): void
    {
        $resolver = new BalikovnaOpeningHoursResolver();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected SimpleXMLElement instance');

        $resolver->resolve('invalid opening hours data');
    }

    private function createOpeningHoursXmlElement(): SimpleXMLElement
    {
        $xmlString = <<<XML
<oteviraci_doby>
    <den name="Pondělí">
        <od_do>
            <od>00:00</od>
            <do>23:59</do>
        </od_do>
    </den>
    <den name="Úterý">
        <od_do>
            <od>12:00</od>
            <do>23:59</do>
        </od_do>
    </den>
</oteviraci_doby>
XML;

        return new SimpleXMLElement($xmlString);
    }
}
