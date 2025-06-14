<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
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
        $table->addColumn('first_name', 'string', ['limit' => 255, 'null' => false])
                ->addColumn('middle_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('last_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('is_admin', 'boolean', ['default' => 0, 'null' => false])
                ->addColumn('phone_no', 'string', ['limit' => 10, 'null' => true]) // Use string to handle leading zeroes
                ->addColumn('alt_phone_no', 'string', ['limit' => 10, 'null' => true])
                ->addColumn('dob', 'date', ['null' => true])
                ->addColumn('profile_image', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('email', 'string', ['limit' => 255, 'null' => false])
                ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
                ->addColumn('job_title', 'string', ['limit' => 100, 'null' => true])
                ->addColumn('is_trainee', 'boolean', ['default' => 0, 'null' => false])
                ->addColumn('remember_me_token', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('token', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('email_verified_at', 'datetime', ['null' => true])
                ->addColumn('token_requested_at', 'datetime', ['null' => true])
                ->addColumn('password_updated_at', 'datetime', ['null' => true])
                ->addColumn('last_login_at', 'datetime', ['null' => true])
                ->addColumn('status', 'boolean', ['default' => 0, 'null' => false])
                ->addColumn('role_id', 'integer', ['null' => false])
                ->addTimestamps() // Adds created and modified columns
                ->create();
    }
}
