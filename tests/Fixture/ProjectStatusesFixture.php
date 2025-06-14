<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProjectStatusesFixture
 */
class ProjectStatusesFixture extends TestFixture
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
                'created' => 1729860400,
                'modified' => 1729860400,
            ],
        ];
        parent::init();
    }
}
