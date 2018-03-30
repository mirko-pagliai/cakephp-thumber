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
use Reflection\ReflectionTrait;
use Thumber\TestSuite\TestCase;
use Thumber\ThumbTrait;
use Thumber\View\Helper\ThumbHelper;

/**
 * ThumbHelperTest class
 */
class ThumbHelperTest extends TestCase
{
    use ReflectionTrait;
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
     * Test for magic `__call()` method
     * @test
     */
    public function testMagicCall()
    {
        $path = '400x400.png';
        $params = ['width' => 200];

        foreach ([
            'crop',
            'fit',
            'resize',
            'resizeCanvas',
        ] as $method) {
            $urlMethod = $method . 'Url';

            foreach ([[], ['fullBase' => false]] as $options) {
                $url = $this->Thumb->$urlMethod($path, $params, $options);
                $this->assertRegExp('/^(http:\/\/localhost)?\/thumb\/[A-z0-9]+/', $url);

                $html = $this->Thumb->$method($path, $params, $options);
                $this->assertHtml(['img' => ['src' => $url, 'alt' => '']], $html);
            }

            //With `url` option
            $url = $this->Thumb->$urlMethod($path, $params);
            $expected = [
                'a' => ['href' => 'http://example'],
                'img' => ['src' => $url, 'alt' => ''],
                '/a',
            ];
            $this->assertHtml($expected, $this->Thumb->$method($path, $params, ['url' => 'http://example']));
        }
    }

    /**
     * Test for magic `_call()` method, called without parameters
     * @expectedException RuntimeException
     * @expectedExceptionMessage Method Thumber\View\Helper\ThumbHelper::noExisting does not exist
     * @test
     */
    public function testMagicCallNoExistingMethod()
    {
        $this->Thumb->noExisting('400x400.png');
    }

    /**
     * Test for magic `_call()` method, called without parameters
     * @expectedException Intervention\Image\Exception\InvalidArgumentException
     * @test
     */
    public function testMagicCallWithoutParameters()
    {
        $this->Thumb->crop('400x400.png');
    }

    /**
     * Test for magic `_call()` method, called without path
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Thumbnail path is missing
     * @test
     */
    public function testMagicCallWithoutPath()
    {
        $this->Thumb->crop();
    }

    /**
     * Test for magic `isUrlMethod()` method
     * @test
     */
    public function testIsUrlMethod()
    {
        $isUrlMethod = function () {
            return $this->invokeMethod($this->Thumb, 'isUrlMethod', func_get_args());
        };

        $this->assertFalse($isUrlMethod('method'));
        $this->assertTrue($isUrlMethod('methodUrl'));
        $this->assertTrue($isUrlMethod('Url'));
        $this->assertFalse($isUrlMethod('method_url'));
    }
}
