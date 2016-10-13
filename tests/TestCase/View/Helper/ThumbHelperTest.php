<?php
/**
 * This file is part of cakephp-thumber.
 *
 * cakephp-thumber is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * cakephp-thumber is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with cakephp-thumber.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */

namespace Thumber\Test\TestCase\View\Helper;

use Cake\Core\Configure;
use Cake\View\View;
use Thumber\TestSuite\TestCase;
use Thumber\View\Helper\ThumbHelper;

/**
 * ThumbHelperTest class
 */
class ThumbHelperTest extends TestCase
{
    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->View = new View();
        $this->Thumb = new ThumbHelper($this->View);
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->Thumb, $this->View);

        //Deletes all thumbnails
        foreach (glob(Configure::read('Thumbs.target') . DS . '*') as $file) {
            unlink($file);
        }
    }

    /**
     * Test for `crop()` and `cropUrl()` methods
     * @return void
     * @test
     */
    public function testCrop()
    {
        $url = $this->Thumb->cropUrl('400x400.png', ['width' => 200]);
        $this->assertRegExp('/^\/thumb\/[A-z0-9]+/', $url);

        $html = $this->Thumb->crop('400x400.png', ['width' => 200]);
        $expected = ['img' => ['src' => $url, 'alt' => '']];
        $this->assertHtml($expected, $html);
    }

    /**
     * Test for `crop()` and `cropUrl()` methods, with the `fullBase` option
     * @return void
     * @test
     */
    public function testCropFullBase()
    {
        $url = $this->Thumb->cropUrl('400x400.png', ['width' => 200], ['fullBase' => true]);
        $this->assertRegExp('/^http:\/\/localhost\/thumb\/[A-z0-9]+/', $url);

        $html = $this->Thumb->crop('400x400.png', ['width' => 200], ['fullBase' => true]);
        $expected = ['img' => ['src' => $url, 'alt' => '']];
        $this->assertHtml($expected, $html);
    }

    /**
     * Test for `crop()` method, called without parameters
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Missing parameters for the `crop` method
     * @test
     */
    public function testCropWithoutParameters()
    {
        $this->Thumb->crop('400x400.png');
    }

    /**
     * Test for `resize()` and `resizeUrl()` methods
     * @return void
     * @test
     */
    public function testResize()
    {
        $url = $this->Thumb->resizeUrl('400x400.png', ['width' => 200]);
        $this->assertRegExp('/^\/thumb\/[A-z0-9]+/', $url);

        $html = $this->Thumb->resize('400x400.png', ['width' => 200]);
        $expected = ['img' => ['src' => $url, 'alt' => '']];
        $this->assertHtml($expected, $html);
    }

    /**
     * Test for `resize()` and `resizeUrl()` methods, with the `fullBase` option
     * @return void
     * @test
     */
    public function testResizeFullBase()
    {
        $url = $this->Thumb->resizeUrl('400x400.png', ['width' => 200], ['fullBase' => true]);
        $this->assertRegExp('/^http:\/\/localhost\/thumb\/[A-z0-9]+/', $url);

        $html = $this->Thumb->resize('400x400.png', ['width' => 200], ['fullBase' => true]);
        $expected = ['img' => ['src' => $url, 'alt' => '']];
        $this->assertHtml($expected, $html);
    }

    /**
     * Test for `resize()` method, called without parameters
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Missing parameters for the `resize` method
     * @test
     */
    public function testResizeWithoutParameters()
    {
        $this->Thumb->resize('400x400.png');
    }
}
