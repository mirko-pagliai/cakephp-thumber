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
use Thumber\ThumbTrait;
use Thumber\Utility\ThumbCreator;
use Thumber\Utility\ThumbManager;

/**
 * ThumbManagerTest class
 */
class ThumbManagerTest extends TestCase
{
    use ThumbTrait;

    /**
     * Internal method to create some thumbs
     */
    protected function createSomeThumbs()
    {
        (new ThumbCreator('400x400.jpg'))->resize(200)->save();
        (new ThumbCreator('400x400.jpg'))->resize(300)->save();
        (new ThumbCreator('400x400.png'))->resize(200)->save();
    }

    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->createSomeThumbs();
    }

    /**
     * Test for `clear()` method
     * @ŧest
     */
    public function testClear()
    {
        $this->assertEquals(2, ThumbManager::clear('400x400.jpg'));

        $this->createSomeThumbs();

        $this->assertEquals(1, ThumbManager::clear('400x400.png'));
    }

    /**
     * Test for `clearAll()` method
     * @ŧest
     */
    public function testClearAll()
    {
        $this->assertEquals(3, ThumbManager::clearAll());
        $this->assertEmpty(ThumbManager::getAll());
    }

    /**
     * Test for `get()` method
     * @ŧest
     */
    public function testGet()
    {
        $this->assertCount(2, ThumbManager::get('400x400.jpg'));
        $this->assertCount(1, ThumbManager::get('400x400.png'));
    }

    /**
     * Test for `getAll()` method
     * @ŧest
     */
    public function testGetAll()
    {
        $this->assertCount(3, ThumbManager::getAll());
    }
}
