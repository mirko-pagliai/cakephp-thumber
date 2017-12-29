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
        $this->assertTrue(ThumbManager::clear('400x400.jpg'));
        $this->assertEquals([
            '5426d23e4b4cb4fff73345b634542ba6_05a9a45566939047c4880be9c21a04b2.png',
        ], ThumbManager::getAll());

        $this->createSomeThumbs();

        $this->assertTrue(ThumbManager::clear('400x400.png'));
        $this->assertEquals([
            '57ad18ce32980e0e5ec6cac848f61bc5_2bbdbd92db066a07672ffbfbdc09e9f7.jpg',
            '57ad18ce32980e0e5ec6cac848f61bc5_bf2a4de9d7436dd52c3968bdbc714701.jpg',
        ], ThumbManager::getAll());
    }

    /**
     * Test for `clearAll()` method
     * @ŧest
     */
    public function testClearAll()
    {
        $this->assertTrue(ThumbManager::clearAll());
        $this->assertEmpty(ThumbManager::getAll());
    }

    /**
     * Test for `get()` method
     * @ŧest
     */
    public function testGet()
    {
        $this->assertEquals([
            '57ad18ce32980e0e5ec6cac848f61bc5_2bbdbd92db066a07672ffbfbdc09e9f7.jpg',
            '57ad18ce32980e0e5ec6cac848f61bc5_bf2a4de9d7436dd52c3968bdbc714701.jpg',
        ], ThumbManager::get('400x400.jpg'));

        $this->assertEquals([
            '5426d23e4b4cb4fff73345b634542ba6_05a9a45566939047c4880be9c21a04b2.png',
        ], ThumbManager::get('400x400.png'));
    }

    /**
     * Test for `getAll()` method
     * @ŧest
     */
    public function testGetAll()
    {
        $this->assertEquals([
            '57ad18ce32980e0e5ec6cac848f61bc5_2bbdbd92db066a07672ffbfbdc09e9f7.jpg',
            '5426d23e4b4cb4fff73345b634542ba6_05a9a45566939047c4880be9c21a04b2.png',
            '57ad18ce32980e0e5ec6cac848f61bc5_bf2a4de9d7436dd52c3968bdbc714701.jpg',
        ], ThumbManager::getAll());

        //With sort
        $this->assertEquals([
            '5426d23e4b4cb4fff73345b634542ba6_05a9a45566939047c4880be9c21a04b2.png',
            '57ad18ce32980e0e5ec6cac848f61bc5_2bbdbd92db066a07672ffbfbdc09e9f7.jpg',
            '57ad18ce32980e0e5ec6cac848f61bc5_bf2a4de9d7436dd52c3968bdbc714701.jpg',
        ], ThumbManager::getAll(true));
    }
}
