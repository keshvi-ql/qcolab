<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateCountries extends AbstractMigration
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
        $table = $this->table('countries');
        $table->addColumn('code', 'string', ['limit' => 2, 'null' => false])
            ->addColumn('name', 'string', ['limit' => 100, 'null' => false])
            ->create();
    }
}
