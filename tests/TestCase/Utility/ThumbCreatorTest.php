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

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Intervention\Image\Exception\NotReadableException as InterventionNotReadableException;
use InvalidArgumentException;
use RuntimeException;
use Thumber\TestSuite\TestCase;
use Tools\Exception\NotReadableException;

/**
 * ThumbCreatorTest class
 */
class ThumbCreatorTest extends TestCase
{
    /**
     * Test for `__construct()` method, passing a no existing file
     * @test
     */
    public function testConstructNoExistingFile()
    {
        $this->expectException(NotReadableException::class);
        $this->expectExceptionMessage('File or directory `' . rtr(WWW_ROOT) . 'img' . DS . 'noExistingFile.gif` is not readable');
        $this->getThumbCreatorInstance('noExistingFile.gif');
    }

    /**
     * Test for `__construct()` method, passing a no existing file from plugin
     * @test
     */
    public function testConstructNoExistingFileFromPlugin()
    {
        Plugin::load('TestPlugin');
        $this->expectException(NotReadableException::class);
        $this->expectExceptionMessage('File or directory `' . rtr(Plugin::path('TestPlugin')) . 'webroot' . DS . 'img' . DS . 'noExistingFile.gif` is not readable');
        $this->getThumbCreatorInstance('TestPlugin.noExistingFile.gif');
    }

    /**
     * Test for `getImageInstance()` method, with unsupported image type for GD driver
     * @ŧest
     */
    public function testGetImageInstanceUnsupportedImageType()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Image type `image/jpeg` is not supported by this driver');
        $exception = new InterventionNotReadableException('Unsupported image type. GD driver is only able to decode JPG, PNG, GIF or WebP files.');
        $thumbCreator = $this->getThumbCreatorInstance();
        $thumbCreator->ImageManager = $this->getMockBuilder(get_class($thumbCreator->ImageManager))->getMock();
        $thumbCreator->ImageManager->method('make')->will($this->throwException($exception));
        $this->invokeMethod($thumbCreator, 'getImageInstance');
    }

    /**
     * Test for `getUrl()` method
     * @ŧest
     */
    public function testGetUrl()
    {
        $result = $this->getThumbCreatorInstanceWithSave()->getUrl();
        $this->assertThumbUrl($result);
        $this->assertTextStartsWith(Configure::read('App.fullBaseUrl'), $result);

        //Without full base
        $result = $this->getThumbCreatorInstanceWithSave()->getUrl(false);
        $this->assertThumbUrl($result);
        $this->assertTextStartsNotWith(Configure::read('App.fullBaseUrl'), $result);

        //Without the `$target` property
        $this->expectException(InvalidArgumentException::class);
        $this->getThumbCreatorInstance()->getUrl();
    }

    /**
     * Test for `$path` property
     * @ŧest
     */
    public function testPath()
    {
        $file = WWW_ROOT . 'img' . DS . '400x400.jpg';
        $thumber = $this->getThumbCreatorInstance();
        $this->assertEquals($this->getProperty($thumber, 'path'), $file);

        $thumber = $this->getThumbCreatorInstance($file);
        $this->assertEquals($this->getProperty($thumber, 'path'), $file);

        //From plugin
        Plugin::load('TestPlugin');
        $file = Plugin::path('TestPlugin') . 'webroot' . DS . 'img' . DS . '400x400.png';
        $thumber = $this->getThumbCreatorInstance('TestPlugin.400x400.png');
        $this->assertEquals($this->getProperty($thumber, 'path'), $file);

        $thumber = $this->getThumbCreatorInstance($file);
        $this->assertEquals($this->getProperty($thumber, 'path'), $file);

        //From remote
        $file = 'http://example.com.png';
        $thumber = $this->getThumbCreatorInstance($file);
        $this->assertEquals($this->getProperty($thumber, 'path'), $file);
    }
}
