<?php declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTablePickupPoints extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('pickup_points', [
            'id' => false,
            'primary_key' => 'id',
            'collation' => 'utf8mb4_czech_ci',
        ]);

        $table
            ->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('externalId', 'string', ['limit' => 255])
            ->addColumn('carrier', 'string', ['limit' => 255])
            ->addColumn('type', 'enum', ['values' => ['box', 'point']])
            ->addColumn('status', 'enum', ['values' => ['available', 'temporarily_unavailable', 'closed', 'terminated']])
            ->addColumn('city', 'string', ['limit' => 255])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('address', 'string', ['limit' => 255])
            ->addColumn('zipCode', 'string', ['limit' => 255])
            ->addColumn('country', 'string', ['limit' => 2])
            ->addColumn('latitude', 'decimal', ['precision' => 10, 'scale' => 8])
            ->addColumn('longitude', 'decimal', ['precision' => 11, 'scale' => 8])
            ->addColumn('openingHours', 'text', ['null' => true])
            ->addColumn('created', 'datetime')
            ->addIndex(['carrier', 'externalId', 'country'], ['unique' => true, 'name' => 'carrier_externalId_country'])
            ->create();
    }
}