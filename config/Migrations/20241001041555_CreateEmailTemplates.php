<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateEmailTemplates extends AbstractMigration
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
        $table = $this->table('email_templates');
        $table->addColumn('slug', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('name', 'text', ['null' => false])
            ->addColumn('subject', 'text', ['null' => false])
            ->addColumn('message', 'text', ['null' => false])
            ->addColumn('placeholders', 'text', ['null' => false])
            ->addTimestamps()
            ->create();


        // After table creation, alter the column types with raw SQL
        $this->execute('ALTER TABLE email_templates MODIFY name MEDIUMTEXT');
        $this->execute('ALTER TABLE email_templates MODIFY subject MEDIUMTEXT');
        $this->execute('ALTER TABLE email_templates MODIFY message LONGTEXT');
        $this->execute('ALTER TABLE email_templates MODIFY placeholders LONGTEXT');
    }
}
