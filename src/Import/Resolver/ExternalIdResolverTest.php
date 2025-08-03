<?php

declare(strict_types=1);

namespace App\Import\Resolver;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ExternalIdResolverTest extends TestCase
{
    #[DataProvider('externalIdDataProvider')]
    public function testResolveExternalId(string $zipCode, string $address, string $expected): void
    {
        $resolver = new ExternalIdResolver();
        $actual = $resolver->resolveExternalId($zipCode, $address);

        Assert::assertSame($expected, $actual);
    }

    public static function externalIdDataProvider(): array
    {
        return [
            'simple lowercase' => [
                '12345',
                'Main Street 10',
                '12345-main-street-10'
            ],
            'diacritics and punctuation' => [
                '37001',
                'České Budějovice, Lannova tř. 12',
                '37001-ceske-budejovice-lannova-tr-12'
            ],
            'uppercase input' => [
                '14000',
                'NA PANKRÁCI 26',
                '14000-na-pankraci-26'
            ],
            'empty address' => [
                '50002',
                '',
                '50002'
            ],
            'empty zip' => [
                '',
                'Pražská 5',
                'prazska-5'
            ],
            'symbols only address' => [
                '11800',
                '@#&$%',
                '11800'
            ],
            'empty spaces' => [
                ' 37001 ',
                ' České Budějovice, Lannova tř. 12 ',
                '37001-ceske-budejovice-lannova-tr-12'
            ],
            'empty zip and address' => [
                '',
                '',
                ''
            ],
        ];
    }
}
