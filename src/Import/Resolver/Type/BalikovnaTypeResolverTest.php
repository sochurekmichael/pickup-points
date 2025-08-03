<?php

declare(strict_types=1);

namespace App\Import\Resolver\Type;

use App\Enum\PickupPointType;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BalikovnaTypeResolverTest extends TestCase
{
    #[DataProvider('typesDataProvider')]
    public function testResolve(string $type, PickupPointType $expected): void
    {
        $resolver = new BalikovnaTypeResolver();

        Assert::assertSame($expected, $resolver->resolve($type));
    }

    /**
     * @return array<string, mixed>
     */
    public static function typesDataProvider(): array
    {
        return [
            'balíkovna-BOX' => [
                'balíkovna-BOX',
                PickupPointType::BOX,
            ],
            'balikovna' => [
                'balikovna',
                PickupPointType::POINT,
            ],
            'balíkovna partner' => [
                'balíkovna partner',
                PickupPointType::POINT,
            ],
            'pošta' => [
                'pošta',
                PickupPointType::POINT,
            ],
            'depo' => [
                'depo',
                PickupPointType::POINT,
            ],
            'fallback to default' => [
                'unknown',
                PickupPointType::POINT,
            ],
        ];
    }
}
