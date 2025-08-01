<?php

declare(strict_types=1);

namespace App\Command;

use PDO;
use Phinx\Config\Config;
use Phinx\Migration\Manager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'phinx:migrate')]
class PhinxMigrationCommand extends Command
{
    private const PHINX_TABLE_NAME = 'phinxlog';
    private const MIGRATION_PATH = __DIR__ . '/../../migrations';

    public function __construct(
        private readonly string $environment,
        private readonly string $database,
        private readonly PDO $pdo,
    ) {
        parent::__construct('phinxMigration');
    }

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $config = [
            'paths' => [
                'migrations' => self::MIGRATION_PATH,
            ],
            'environments' => [
                'default_migration_table' => self::PHINX_TABLE_NAME,
                'default_environment' => 'development',
                'development' => [
                    'name' => $this->database,
                    'connection' => $this->pdo,
                ],
            ],
        ];

        $config = new Config($config);
        $manager = new Manager($config, $input, $output);
        $manager->migrate($this->environment);

        $output->writeln('<info>Phinx migrations executed successfully.</info>');

        return Command::SUCCESS;
    }
}
