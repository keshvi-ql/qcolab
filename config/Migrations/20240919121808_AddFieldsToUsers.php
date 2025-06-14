<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class AddFieldsToUsers extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('alt_email', 'string', ['limit' => 255, 'null' => true, 'default' => null])
            ->addColumn('address', 'text', ['null' => true, 'default' => null])
            ->addColumn('alt_address', 'text', ['null' => true, 'default' => null])
            ->addColumn('skype', 'string', ['limit' => 100, 'null' => true, 'default' => null])
            ->addColumn('employee_code', 'string', ['limit' => 50, 'null' => true, 'default' => null])
            ->addColumn('pan_no', 'string', ['limit' => 100, 'null' => true, 'default' => null])
            ->addColumn('bank_name', 'string', ['limit' => 100, 'null' => true, 'default' => null])
            ->addColumn('bank_account_no', 'string', ['limit' => 100, 'null' => true, 'default' => null])
            ->addColumn('security_deposit_amount', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => true, 'default' => null])
            ->addColumn('salary', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => true, 'default' => null])
            ->addColumn('date_of_joining', 'date', ['null' => true, 'default' => null])
            ->addColumn('increment_month', 'string', ['limit' => 50, 'null' => true, 'default' => null])
            ->addColumn('is_bde', 'boolean', ['default' => 0, 'null' => false]);
        $table->update();
    }
}
