<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * NotificationRecipientsFixture
 */
class NotificationRecipientsFixture extends TestFixture
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
                'notification_id' => 1,
                'user_id' => 1,
                'is_read' => 1,
                'read_at' => '2024-10-18 07:17:52',
                'deleted' => 1,
            ],
        ];
        parent::init();
    }
}
