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
namespace Thumber\Cake\Test\TestCase\Utility;

use Cake\Core\Configure;
use ErrorException;
use Intervention\Image\ImageManager;
use Thumber\Cake\TestSuite\TestCase;
use Thumber\Exception\NotReadableImageException;
use Thumber\Exception\UnsupportedImageTypeException;
use Tools\Exception\NotReadableException;
use Tools\Exception\NotWritableException;
use Tools\TestSuite\ReflectionTrait;

/**
 * ThumbCreatorTest class
 */
class ThumbCreatorTest extends TestCase
{
    use ReflectionTrait;

    /**
     * Test for `__construct()` method, passing a no existing file
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::__construct()
     */
    public function testConstructNoExistingFile(): void
    {
        $this->expectException(NotReadableException::class);
        $this->expectExceptionMessage('File or directory `tests' . DS . 'test_app' . DS . 'webroot' . DS . 'img' . DS . 'noExistingFile.gif` is not readable');
        $this->getThumbCreatorInstance('noExistingFile.gif');
    }

    /**
     * Test for `__construct()` method, passing a no existing file from plugin
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::__construct()
     */
    public function testConstructNoExistingFileFromPlugin(): void
    {
        $this->loadPlugins(['TestPlugin' => []]);
        $this->expectException(NotReadableException::class);
        $this->expectExceptionMessage('File or directory `tests' . DS . 'test_app' . DS . 'Plugin' . DS . 'TestPlugin' . DS . 'webroot' . DS . 'img' . DS . 'noExistingFile.gif` is not readable');
        $this->getThumbCreatorInstance('TestPlugin.noExistingFile.gif');
    }

    /**
     * Test for `getImageInstance()` method, with unsupported image type for GD driver
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::getImageInstance()
     */
    public function testGetImageInstanceUnsupportedImageType(): void
    {
        $this->expectException(UnsupportedImageTypeException::class);
        $this->expectExceptionMessage('Image type `image/jpeg` is not supported by this driver');
        $thumbCreator = $this->getThumbCreatorInstance();
        $thumbCreator->ImageManager = $this->createPartialMock(ImageManager::class, ['make']);
        $thumbCreator->ImageManager->method('make')->willThrowException(new UnsupportedImageTypeException('Image type `image/jpeg` is not supported by this driver'));
        $this->invokeMethod($thumbCreator, 'getImageInstance');
    }

    /**
     * Test for `getImageInstance()` method, with a not readable image
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::getImageInstance()
     */
    public function testGetImageInstanceNotReadableImageException(): void
    {
        $this->expectException(NotReadableImageException::class);
        $this->expectExceptionMessage('Unable to read image from file `anExampleFile`');
        $thumbCreator = $this->getThumbCreatorInstance();
        $thumbCreator->ImageManager = $this->createPartialMock(ImageManager::class, ['make']);
        $thumbCreator->ImageManager->method('make')->willThrowException(new NotReadableImageException('Unable to read image from file `anExampleFile`'));
        $this->invokeMethod($thumbCreator, 'getImageInstance');
    }

    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::getUrl()
     */
    public function testGetUrl(): void
    {
        $result = $this->getThumbCreatorInstanceWithSave()->getUrl();
        $this->assertThumbUrl($result);
        $this->assertTextStartsWith(Configure::read('App.fullBaseUrl'), $result);

        //Without full base
        $result = $this->getThumbCreatorInstanceWithSave()->getUrl(false);
        $this->assertThumbUrl($result);
        $this->assertTextStartsNotWith(Configure::read('App.fullBaseUrl'), $result);

        //Without the `$target` property
        $this->expectExceptionMessage('Missing path of the generated thumbnail. Probably the `save()` method has not been invoked');
        $this->getThumbCreatorInstance()->getUrl();
    }

    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::save()
     */
    public function testSave(): void
    {
        //When unable to create the file
        $this->assertException(function () {
            $this->getThumbCreatorInstance()->save();
        }, ErrorException::class, 'No valid method called before the `save()` method');

        //Without a valid method called before
        $this->skipIf(IS_WIN);
        $this->assertException(function () {
            $this->getThumbCreatorInstance()->resize(200)->save(['target' => DS . 'noExisting']);
        }, NotWritableException::class, 'Unable to create file `' . DS . 'noExisting`');
    }
}
