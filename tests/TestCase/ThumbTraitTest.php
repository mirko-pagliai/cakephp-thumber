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
namespace Thumber\Test\TestCase;

use Cake\Core\Configure;
use Thumber\TestSuite\TestCase;
use Thumber\ThumbTrait;

/**
 * ThumbTraitTest class
 */
class ThumbTraitTest extends TestCase
{
    use ThumbTrait;

    /**
     * Test for `getDriver()` method
     * @test
     */
    public function testGetDriver()
    {
        $this->assertNotEmpty($this->getDriver());
    }

    /**
     * Test for `getExtension()` method
     * @test
     */
    public function testGetExtension()
    {
        foreach ([
            'file.jpg',
            'file.jpg?',
            'file.jpg?param',
            'file.jpg?param=value',
            'file.jpeg',
        ] as $filename) {
            $this->assertEquals('jpg', $this->getExtension($filename));
        }

        $this->assertEquals('tiff', $this->getExtension('file.tiff'));
        $this->assertEquals('tiff', $this->getExtension('file.tif'));
    }

    /**
     * Test for `getPath()` method
     * @test
     */
    public function testGetPath()
    {
        $this->assertEquals(Configure::read(THUMBER . '.target'), $this->getPath());
        $this->assertEquals(Configure::read(THUMBER . '.target') . DS . 'file.jpg', $this->getPath('file.jpg'));
    }

    /**
     * Test for `getSupportedFormats()` method
     * @test
     */
    public function testGetSupportedFormats()
    {
        $this->assertNotEmpty($this->getSupportedFormats());
        $this->assertTrue(is_array($this->getSupportedFormats()));
    }
}
