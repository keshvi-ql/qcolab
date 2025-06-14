<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateClients extends AbstractMigration
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
        $table = $this->table('clients');
        $table->addColumn('first_name', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('last_name', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('email', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('alt_email', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('phone_no', 'string', ['limit' => 10, 'null' => true])
            ->addColumn('skype', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('country', 'integer', ['limit' => 11, 'null' => true])
            ->addColumn('source', 'integer', ['limit' => 11, 'null' => true])
            ->addColumn('favorite', 'boolean', ['default' => 0, 'null' => true])
            ->addColumn('note', 'text', ['null' => true])
            ->addTimestamps()
            ->create();
    }
}
