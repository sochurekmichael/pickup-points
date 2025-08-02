<?php

declare(strict_types=1);

namespace App\Import;

use App\Enum\Country;
use App\Repository\PickupPointRepository;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class ImporterService
{
    private const BATCH_SIZE = 800;

    /**
     * @param array<ImporterInterface> $importers
     */
    public function __construct(
        private readonly array $importers,
        private readonly PickupPointRepository $repository
    ) {
    }

    public function import(OutputInterface $output): void
    {
        foreach ($this->importers as $importer) {
            $output->writeln("🔄 Importing carrier: <info>{$importer->getCarrier()->value}</info>");

            $this->importCarrier($importer, $output);
        }
    }

    private function importCarrier(ImporterInterface $importer, OutputInterface $output): void
    {
        $batch = [];
        $count = 0;

        $this->repository->markAllAsOutdated($importer->getCarrier(), Country::CZ);

        $progress = new ProgressBar($output);
        $progress->start();

        foreach ($importer->fetchPickupPoints() as $pickupPoint) {
            $batch[] = $pickupPoint;
            ++$count;
            $progress->advance();

            if (count($batch) === self::BATCH_SIZE) {
                $this->repository->upsert($batch);
                $batch = [];
            }
        }

        if ($batch !== []) {
            $this->repository->upsert($batch);
        }

        $progress->finish();
        $output->writeln(" <comment>($count records)</comment>");

        $this->repository->markNotCurrentAsTemporarilyUnavailable($importer->getCarrier(), Country::CZ);
    }
}
