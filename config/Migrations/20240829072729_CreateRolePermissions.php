<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateRolePermissions extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('role_permissions');
        $table->addColumn('role_id', 'integer', ['null' => false])
              ->addColumn('permission_id', 'integer', ['null' => false])
              ->create();
    }
}
