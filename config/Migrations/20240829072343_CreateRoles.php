<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateRoles extends AbstractMigration
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
        $table = $this->table('roles');
        $table->addColumn('name', 'string', ['limit' => 50, 'null' => false])
              ->addTimestamps() // Optional: Adds created and modified columns
              ->create();
    }
}
