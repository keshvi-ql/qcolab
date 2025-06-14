<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BidsFixture
 */
class BidsFixture extends TestFixture
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
                'url' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'source' => 1,
                'profile' => 1,
                'type' => 'Lorem ipsum dolor sit amet',
                'rate' => 1.5,
                'created_by' => 1,
                'created' => 1729831275,
                'modified' => 1729831275,
                'deleted' => 1,
            ],
        ];
        parent::init();
    }
}
