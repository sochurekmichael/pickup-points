<?php declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ChangeColumnsNullableInPickupPoints extends AbstractMigration
{
    public function change(): void
    {
        $this->table('pickup_points')
            ->changeColumn('externalId', 'string', ['null' => false])
            ->changeColumn('carrier', 'string', ['null' => false])
            ->changeColumn('city', 'string', ['null' => false])
            ->changeColumn('name', 'string', ['null' => false])
            ->changeColumn('address', 'string', ['null' => false])
            ->changeColumn('zipCode', 'string', ['null' => false])
            ->changeColumn('country', 'string', ['null' => false])
            ->changeColumn('type', 'enum', [
                'values' => ['box', 'point'],
                'null' => false]
            )
            ->changeColumn('status', 'enum', [
                'values' => ['available', 'temporarily_unavailable', 'closed', 'terminated'],
                'null' => false
            ])
            ->changeColumn('created', 'datetime', ['null' => false])
            ->update();
    }
}