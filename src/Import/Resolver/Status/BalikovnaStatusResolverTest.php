<?php

declare(strict_types=1);

namespace App\Import\Resolver\Status;

use App\Enum\PickupPointStatus;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class BalikovnaStatusResolverTest extends TestCase
{
    public function testAlwaysReturnsAvailable(): void
    {
        $resolver = new BalikovnaStatusResolver();
        $result = $resolver->resolve('any status string');

        Assert::assertSame(PickupPointStatus::AVAILABLE, $result);
    }
}
