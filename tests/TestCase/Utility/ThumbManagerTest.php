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

use Thumber\Cake\TestSuite\TestCase;
use Thumber\Cake\Utility\ThumbManager;

/**
 * ThumbManagerTest class
 */
class ThumbManagerTest extends TestCase
{
    /**
     * @var \Thumber\Cake\Utility\ThumbManager
     */
    protected $ThumbManager;

    /**
     * Called before every test method
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->ThumbManager = new ThumbManager();

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
        $result = $this->ThumbManager->getAll();
        $resultWithSort = $this->ThumbManager->getAll(true);
        $this->assertCount(3, $result);
        $this->assertCount(3, $resultWithSort);
        $this->assertEquals($result, $resultWithSort);
    }
}
