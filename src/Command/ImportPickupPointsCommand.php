<?php

declare(strict_types=1);

namespace App\Command;

use App\Import\ImporterService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'import:start')]
class ImportPickupPointsCommand extends Command
{
    public function __construct(
        private readonly ImporterService $importerService
    ) {
        parent::__construct("importPickupPoints");
    }

    public function __invoke(OutputInterface $output): int
    {
        $output->writeln("<info>📦 Import pickup points is starting...</info>");

        $this->importerService->import($output);

        $output->writeln("<info>🎉 Successfully imported!</info>");


        return Command::SUCCESS;
    }
}
