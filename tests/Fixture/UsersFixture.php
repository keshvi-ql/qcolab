<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'first_name' => 'Lorem ipsum dolor sit amet',
                'middle_name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'is_admin' => 1,
                'phone_no' => 'Lorem ip',
                'alt_phone_no' => 'Lorem ip',
                'dob' => '2024-09-19',
                'profile_image' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'job_title' => 'Lorem ipsum dolor sit amet',
                'is_trainee' => 1,
                'remember_me_token' => 'Lorem ipsum dolor sit amet',
                'token' => 'Lorem ipsum dolor sit amet',
                'email_verified_at' => '2024-09-19 12:50:40',
                'token_requested_at' => '2024-09-19 12:50:40',
                'password_updated_at' => '2024-09-19 12:50:40',
                'last_login_at' => '2024-09-19 12:50:40',
                'status' => 1,
                'role_id' => 1,
                'created' => 1726750240,
                'modified' => 1726750240,
                'gender' => 'Lorem ip',
                'alt_email' => 'Lorem ipsum dolor sit amet',
                'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'alt_address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'skype' => 'Lorem ipsum dolor sit amet',
                'employee_code' => 'Lorem ipsum dolor sit amet',
                'pan_no' => 'Lorem ipsum dolor sit amet',
                'bank_name' => 'Lorem ipsum dolor sit amet',
                'bank_account_no' => 'Lorem ipsum dolor sit amet',
                'security_deposit_amount' => 1.5,
                'salary' => 1.5,
                'date_of_joining' => '2024-09-19 12:50:40',
                'increment_month' => 'Lorem ipsum dolor sit amet',
                'is_bde' => 1,
            ],
        ];
        parent::init();
    }
}
