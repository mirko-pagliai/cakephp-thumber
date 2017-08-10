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
namespace Thumber\Test\TestCase\View\Helper;

use Cake\View\View;
use Thumber\TestSuite\TestCase;
use Thumber\ThumbTrait;
use Thumber\View\Helper\ThumbHelper;

/**
 * ThumbHelperTest class
 */
class ThumbHelperTest extends TestCase
{
    use ThumbTrait;

    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Thumb = new ThumbHelper(new View);
    }

    /**
     * Test for `crop()` and `cropUrl()` methods
     * @return void
     * @test
     */
    public function testCrop()
    {
        $url = $this->Thumb->cropUrl('400x400.png', ['width' => 200]);
        $this->assertRegExp('/^http:\/\/localhost\/thumb\/[A-z0-9]+/', $url);

        $html = $this->Thumb->crop('400x400.png', ['width' => 200]);
        $expected = ['img' => ['src' => $url, 'alt' => '']];
        $this->assertHtml($expected, $html);
    }

    /**
     * Test for `crop()` and `cropUrl()` methods, with the `fullBase` option as
     *  `false`
     * @return void
     * @test
     */
    public function testCropFullBaseFalse()
    {
        $url = $this->Thumb->cropUrl('400x400.png', ['width' => 200], ['fullBase' => false]);
        $this->assertRegExp('/^\/thumb\/[A-z0-9]+/', $url);

        $html = $this->Thumb->crop('400x400.png', ['width' => 200], ['fullBase' => false]);
        $expected = ['img' => ['src' => $url, 'alt' => '']];
        $this->assertHtml($expected, $html);
    }

    /**
     * Test for `crop()` method, called without parameters
     * @expectedException Intervention\Image\Exception\InvalidArgumentException
     * @test
     */
    public function testCropWithoutParameters()
    {
        $this->Thumb->crop('400x400.png');
    }

    /**
     * Test for `fit()` and `fitUrl()` methods
     * @return void
     * @test
     */
    public function testFit()
    {
        $url = $this->Thumb->fitUrl('400x400.png', ['width' => 200]);
        $this->assertRegExp('/^http:\/\/localhost\/thumb\/[A-z0-9]+/', $url);

        $html = $this->Thumb->fit('400x400.png', ['width' => 200]);
        $expected = ['img' => ['src' => $url, 'alt' => '']];
        $this->assertHtml($expected, $html);
    }

    /**
     * Test for `fit()` and `fitUrl()` methods, with the `fullBase` option as
     *  `false`
     * @return void
     * @test
     */
    public function testFitFullBaseFalse()
    {
        $url = $this->Thumb->fitUrl('400x400.png', ['width' => 200], ['fullBase' => false]);
        $this->assertRegExp('/^\/thumb\/[A-z0-9]+/', $url);

        $html = $this->Thumb->fit('400x400.png', ['width' => 200], ['fullBase' => false]);
        $expected = ['img' => ['src' => $url, 'alt' => '']];
        $this->assertHtml($expected, $html);
    }

    /**
     * Test for `fit()` method, called without parameters
     * @expectedException Intervention\Image\Exception\InvalidArgumentException
     * @test
     */
    public function testFitWithoutParameters()
    {
        $this->Thumb->fit('400x400.png');
    }

    /**
     * Test for `resize()` and `resizeUrl()` methods
     * @return void
     * @test
     */
    public function testResize()
    {
        $url = $this->Thumb->resizeUrl('400x400.png', ['width' => 200]);
        $this->assertRegExp('/^http:\/\/localhost\/thumb\/[A-z0-9]+/', $url);

        $html = $this->Thumb->resize('400x400.png', ['width' => 200]);
        $expected = ['img' => ['src' => $url, 'alt' => '']];
        $this->assertHtml($expected, $html);
    }

    /**
     * Test for `resize()` and `resizeUrl()` methods, with the `fullBase` option
     *  as `false`
     * @return void
     * @test
     */
    public function testResizeFullBaseFalse()
    {
        $url = $this->Thumb->resizeUrl('400x400.png', ['width' => 200], ['fullBase' => false]);
        $this->assertRegExp('/^\/thumb\/[A-z0-9]+/', $url);

        $html = $this->Thumb->resize('400x400.png', ['width' => 200], ['fullBase' => false]);
        $expected = ['img' => ['src' => $url, 'alt' => '']];
        $this->assertHtml($expected, $html);
    }

    /**
     * Test for `resize()` method, called without parameters
     * @expectedException Intervention\Image\Exception\InvalidArgumentException
     * @test
     */
    public function testResizeWithoutParameters()
    {
        $this->Thumb->resize('400x400.png');
    }

    /**
     * Test for `url` option
     * @return void
     * @test
     */
    public function testUrlOption()
    {
        $url = $this->Thumb->resizeUrl('400x400.png', ['width' => 200]);

        $html = $this->Thumb->resize('400x400.png', ['width' => 200], ['url' => 'http://example']);
        $expected = [
            'a' => ['href' => 'http://example'],
            'img' => ['src' => $url, 'alt' => ''],
            '/a',
        ];
        $this->assertHtml($expected, $html);
    }
}
