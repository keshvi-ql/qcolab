<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LeadStatusesFixture
 */
class LeadStatusesFixture extends TestFixture
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
                'color' => 'Lorem ip',
                'created' => 1729750279,
                'modified' => 1729750279,
            ],
        ];
        parent::init();
    }
}
