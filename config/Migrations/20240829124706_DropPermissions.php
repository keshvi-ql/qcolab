<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class DropPermissions extends AbstractMigration
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
        $this->table('permissions')->drop()->save();
    }

    public function down()
    {
        $table = $this->table('permissions');
        $table->addColumn('controller', 'string', ['limit' => 100, 'null' => false])
              ->addColumn('action', 'string', ['limit' => 100, 'null' => false])
              ->addTimestamps() // Optional: Adds created and modified columns
              ->create();
    }
}
