<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * HolidaysFixture
 */
class HolidaysFixture extends TestFixture
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
                'title' => 'Lorem ipsum dolor sit amet',
                'start_date' => '2024-10-26',
                'end_date' => '2024-10-26',
                'created' => 1729922965,
                'modified' => 1729922965,
                'deleted' => 1,
            ],
        ];
        parent::init();
    }
}
