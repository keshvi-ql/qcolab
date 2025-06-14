<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PausesFixture
 */
class PausesFixture extends TestFixture
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
                'time_log_id' => 1,
                'pause_time' => '2024-10-10 10:29:27',
                'resume_time' => '2024-10-10 10:29:27',
                'pause_duration' => '10:29:27',
                'created' => 1728556167,
                'modified' => 1728556167,
            ],
        ];
        parent::init();
    }
}
