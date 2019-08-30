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

use BadMethodCallException;
use Cake\Core\Configure;
use Intervention\Image\Exception\NotReadableException as InterventionNotReadableException;
use Intervention\Image\ImageManager;
use InvalidArgumentException;
use MeTools\Core\Plugin;
use PhpThumber\Exception\NotReadableImageException;
use PhpThumber\Exception\UnsupportedImageTypeException;
use Thumber\TestSuite\TestCase;
use Thumber\Utility\ThumbCreator;
use Tools\Exception\NotReadableException;
use Tools\Exception\NotWritableException;

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
        $this->expectExceptionMessage('File or directory `' . rtr(WWW_ROOT) . DS . 'img' . DS . 'noExistingFile.gif` is not readable');
        $this->getThumbCreatorInstance('noExistingFile.gif');
    }

    /**
     * Test for `__construct()` method, passing a no existing file from plugin
     * @test
     */
    public function testConstructNoExistingFileFromPlugin()
    {
        $this->loadPlugins(['TestPlugin']);
        $this->expectException(NotReadableException::class);
        $this->expectExceptionMessage('File or directory `' . rtr(Plugin::path('TestPlugin')) . DS . 'webroot' . DS . 'img' . DS . 'noExistingFile.gif` is not readable');
        $this->getThumbCreatorInstance('TestPlugin.noExistingFile.gif');
    }

    /**
     * Test for `getImageInstance()` method, with unsupported image type for GD driver
     * @ŧest
     */
    public function testGetImageInstanceUnsupportedImageType()
    {
        $this->expectException(UnsupportedImageTypeException::class);
        $this->expectExceptionMessage('Image type `image/jpeg` is not supported by this driver');
        $exception = new InterventionNotReadableException('Unsupported image type. GD driver is only able to decode JPG, PNG, GIF or WebP files.');
        $thumbCreator = $this->getThumbCreatorInstance();
        $thumbCreator->ImageManager = $this->getMockBuilder(ImageManager::class)
            ->setMethods(['make'])
            ->getMock();
        $thumbCreator->ImageManager->method('make')->will($this->throwException($exception));
        $this->invokeMethod($thumbCreator, 'getImageInstance');
    }

    /**
     * Test for `getImageInstance()` method, with a not readable image
     * @ŧest
     */
    public function testGetImageInstanceNotReadableImageException()
    {
        $expectedException = NotReadableImageException::class;
        $expectedExceptionMessage = 'Unable to read image from file `tests/bootstrap.php`';
        if (THUMBER_DRIVER != 'imagick') {
            $expectedException = UnsupportedImageTypeException::class;
            $expectedExceptionMessage = 'Image type `text/x-php` is not supported by this driver';
        }
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->getThumbCreatorInstanceWithSave(TESTS . 'bootstrap.php');
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
        $this->loadPlugins(['TestPlugin']);

        foreach ([
            WWW_ROOT . 'img' . DS . '400x400.jpg',
            Plugin::path('TestPlugin') . 'webroot' . DS . 'img' . DS . '400x400.png',
        ] as $file) {
            $thumber = $this->getThumbCreatorInstance($file);
            $this->assertEquals($this->getProperty($thumber, 'path'), $file);
        }
    }

    /**
     * Test for `save()` method
     * @test
     */
    public function testSave()
    {
        //When unable to create the file
        $this->assertException(BadMethodCallException::class, function () {
            $this->getThumbCreatorInstance()->save();
        }, 'No valid method called before the `save()` method');

        //Without a valid method called before
        $this->assertException(NotWritableException::class, function () {
            $this->getThumbCreatorInstance()->resize(200)->save(['target' => DS . 'noExisting']);
        }, 'Unable to create file ``');
    }
}
