<?php declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddColumnIsCurrentToPickupPoints extends AbstractMigration
{
    public function change(): void
    {
        $this->table('pickup_points')
            ->addColumn('isCurrent', 'boolean', [
                'default' => false,
                'after' => 'created',
                'null' => false
            ])
            ->addIndex(['isCurrent'], ['name' => 'idx_pickup_points_is_current'])
            ->update();
    }
}