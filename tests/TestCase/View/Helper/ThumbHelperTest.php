<?php
declare(strict_types=1);

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
namespace Thumber\Cake\Test\TestCase\View\Helper;

use BadMethodCallException;
use Cake\View\View;
use InvalidArgumentException;
use Thumber\Cake\TestSuite\TestCase;
use Thumber\Cake\View\Helper\ThumbHelper;

/**
 * ThumbHelperTest class
 */
class ThumbHelperTest extends TestCase
{
    /**
     * Called before every test method
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->Thumb = new ThumbHelper(new View());
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
                $this->assertThumbUrl($url);

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

        //Calling a no existing method
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method `Thumber\Cake\Utility\ThumbCreator::noExisting()` does not exist');
        $this->Thumb->noExisting('400x400.png');
    }

    /**
     * Test for magic `_call()` method, called without parameters
     * @test
     */
    public function testMagicCallWithoutParameters()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->Thumb->crop('400x400.png');
    }

    /**
     * Test for magic `_call()` method, called without path
     * @test
     */
    public function testMagicCallWithoutPath()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Thumbnail path is missing');
        $this->Thumb->crop();
    }

    /**
     * Test for magic `isUrlMethod()` method
     * @test
     */
    public function testIsUrlMethod()
    {
        $isUrlMethod = function (string $methodName) {
            return $this->invokeMethod($this->Thumb, 'isUrlMethod', [$methodName]);
        };

        $this->assertFalse($isUrlMethod('method'));
        $this->assertTrue($isUrlMethod('methodUrl'));
        $this->assertTrue($isUrlMethod('Url'));
        $this->assertFalse($isUrlMethod('method_url'));
    }
}
