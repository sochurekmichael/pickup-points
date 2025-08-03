<?php

declare(strict_types=1);

namespace App\Command;

use App\Import\ImporterService;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class ImportPickupPointsCommandTest extends TestCase
{
    public function testCommandIsSuccessfullyFinished(): void
    {
        $outputMock = $this->createMock(OutputInterface::class);

        $command = new ImportPickupPointsCommand(
            $this->createImporterMock($outputMock)
        );
        $statusCode = $command($outputMock);

        Assert::assertSame(Command::SUCCESS, $statusCode);
    }

    private function createImporterMock(OutputInterface $output): ImporterService&MockObject
    {
        $mock = $this->createMock(ImporterService::class);
        $mock->expects($this->once())
            ->method('import')
            ->with($output);

        return $mock;
    }
}
