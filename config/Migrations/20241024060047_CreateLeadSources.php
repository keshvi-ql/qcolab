<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateLeadSources extends AbstractMigration
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
        $table = $this->table('lead_sources');
        $table->addColumn('title', 'string', ['limit' => 100, 'null' => false])
            ->addTimestamps()
            ->create();
    }
}
