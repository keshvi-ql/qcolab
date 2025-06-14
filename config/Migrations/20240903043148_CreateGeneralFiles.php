<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateGeneralFiles extends AbstractMigration
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
        $table = $this->table('general_files');
        $table->addColumn('file_name', 'text', ['null' => false])
                ->addColumn('file_id', 'text', ['null' => true])
                ->addColumn('description', 'text', ['null' => true])
                ->addColumn('file_size', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => false])
                ->addColumn('user_id', 'integer', ['default' => 0, 'null' => false])
                ->addColumn('uploaded_by', 'integer', ['null' => false])
                ->addColumn('deleted', 'boolean', ['default' => 0,'null' => false])
                ->addTimestamps()
                ->create();
    }
}
