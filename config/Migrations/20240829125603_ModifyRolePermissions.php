<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class ModifyRolePermissions extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $table = $this->table('role_permissions');
        $table->removeColumn('permission_id')
              ->addColumn('controller', 'string', ['limit' => 100, 'null' => false])
              ->addColumn('action', 'string', ['limit' => 100, 'null' => false])
              ->addIndex(['role_id', 'controller', 'action'], ['unique' => true])
              ->update();
    }

    public function down()
    {
        $table = $this->table('role_permissions');
        $table->removeColumn('controller')
              ->removeColumn('action')
              ->addColumn('permission_id', 'integer', ['null' => false])
              ->addIndex(['role_id', 'permission_id'], ['unique' => true])
              ->update();
    }
}
