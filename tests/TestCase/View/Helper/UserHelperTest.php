<?php
declare(strict_types=1);

namespace App\Test\TestCase\View\Helper;

use App\View\Helper\UserHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\UserHelper Test Case
 */
class UserHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\View\Helper\UserHelper
     */
    protected $User;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->User = new UserHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->User);

        parent::tearDown();
    }
}
