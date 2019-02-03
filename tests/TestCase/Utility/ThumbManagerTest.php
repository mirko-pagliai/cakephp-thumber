<?php
/**
 * This file is part of cakephp-thumber.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/cakephp-thumber
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Thumber\Test\TestCase\Utility;

use Thumber\TestSuite\TestCase;
use Thumber\Utility\ThumbManager;

/**
 * ThumbManagerTest class
 */
class ThumbManagerTest extends TestCase
{
    /**
     * @var \Thumber\Utility\ThumbManager
     */
    protected $ThumbManager;

    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->ThumbManager = new ThumbManager;

        $this->createSomeThumbs();
    }

    /**
     * Test for `clear()` method
     * @group onlyUnix
     * @ŧest
     */
    public function testClear()
    {
        $this->assertEquals(2, $this->ThumbManager->clear('400x400.jpg'));

        $this->createSomeThumbs();
        $this->assertEquals(1, $this->ThumbManager->clear('400x400.png'));
    }

    /**
     * Test for `clear()` method, with error
     * @group onlyUnix
     * @ŧest
     */
    public function testClearWithError()
    {
        $class = $this->getMockBuilder(get_class($this->ThumbManager))
            ->setMethods(['get'])
            ->getMock();
        $class->method('get')->will($this->returnValue(['noExisting']));

        $this->assertFalse($class->clear('noExisting'));
    }

    /**
     * Test for `clearAll()` method
     * @group onlyUnix
     * @ŧest
     */
    public function testClearAll()
    {
        $this->assertEquals(3, $this->ThumbManager->clearAll());
        $this->assertEmpty($this->ThumbManager->getAll());
    }

    /**
     * Test for `get()` method
     * @ŧest
     */
    public function testGet()
    {
        $this->assertCount(2, $this->ThumbManager->get('400x400.jpg'));
        $this->assertCount(1, $this->ThumbManager->get('400x400.png'));
    }

    /**
     * Test for `getAll()` method
     * @ŧest
     */
    public function testGetAll()
    {
        $this->assertGreaterThanOrEqual(3, $this->ThumbManager->getAll());
    }
}
