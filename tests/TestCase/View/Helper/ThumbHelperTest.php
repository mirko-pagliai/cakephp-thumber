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

namespace Thumber\Test\TestCase\View\Helper;

use Cake\View\View;
use Thumber\TestSuite\TestCase;
use Thumber\Utility\ThumbCreator;
use Thumber\View\Helper\ThumbHelper;

/**
 * ThumbHelperTest class
 */
class ThumbHelperTest extends TestCase
{
    /**
     * @var \Thumber\View\Helper\ThumbHelper
     */
    protected ThumbHelper $Thumb;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadPlugins(['Thumber' => []]);

        $this->Thumb ??= new ThumbHelper(new View());
    }

    /**
     * @test
     * @uses \Thumber\View\Helper\ThumbHelper::__call()
     */
    public function testMagicCall(): void
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
        $this->expectExceptionMessage('Method `' . ThumbCreator::class . '::noExisting()` does not exist');
        $this->Thumb->noExisting('400x400.png');
    }

    /**
     * Test for magic `_call()` method, called without parameters
     * @test
     * @uses \Thumber\View\Helper\ThumbHelper::__call()
     */
    public function testMagicCallWithoutParameters(): void
    {
        $this->expectExceptionMessage('You have to set at least the width for the `' . ThumbCreator::class . '::crop()` method');
        $this->Thumb->crop('400x400.png');
    }

    /**
     * Test for magic `_call()` method, called without path
     * @test
     * @uses \Thumber\View\Helper\ThumbHelper::__call()
     */
    public function testMagicCallWithoutPath(): void
    {
        $this->expectExceptionMessage('Thumbnail path is missing');
        $this->Thumb->crop();
    }
}
