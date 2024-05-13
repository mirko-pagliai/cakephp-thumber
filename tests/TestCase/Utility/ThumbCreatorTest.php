<?php
/** @noinspection PhpUnhandledExceptionInspection */
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

namespace Thumber\Test\TestCase\Utility;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Intervention\Image\ImageManager;
use LogicException;
use Thumber\TestSuite\TestCase;

/**
 * ThumbCreatorTest class
 * @uses \Thumber\Utility\ThumbCreator
 */
class ThumbCreatorTest extends TestCase
{
    /**
     * Test for `__construct()` method, passing a no existing file
     * @test
     * @uses \Thumber\Utility\ThumbCreator::__construct()
     */
    public function testConstructNoExistingFile(): void
    {
        $this->expectExceptionMessage('File or directory `' . WWW_ROOT . 'img' . DS . 'noExistingFile.gif` is not readable');
        $this->getThumbCreatorInstance('noExistingFile.gif');
    }

    /**
     * Test for `__construct()` method, passing a no existing file from plugin
     * @test
     * @uses \Thumber\Utility\ThumbCreator::__construct()
     */
    public function testConstructNoExistingFileFromPlugin(): void
    {
        $this->loadPlugins(['TestPlugin' => []]);
        $this->expectExceptionMessage('File or directory `' . Plugin::path('TestPlugin') . 'webroot' . DS . 'img' . DS . 'noExistingFile.gif` is not readable');
        $this->getThumbCreatorInstance('TestPlugin.noExistingFile.gif');
    }

    /**
     * Test for `getImageInstance()` method, with unsupported image type for GD driver
     * @test
     * @uses \Thumber\Utility\ThumbCreator::getImageInstance()
     */
    public function testGetImageInstanceUnsupportedImageType(): void
    {
        $this->expectExceptionMessage('Image type `image/jpeg` is not supported by this driver');
        $ThumbCreator = $this->getThumbCreatorInstance();
        $ThumbCreator->ImageManager = $this->createPartialMock(ImageManager::class, ['make']);
        $ThumbCreator->ImageManager->method('make')
            ->willThrowException(new LogicException('Image type `image/jpeg` is not supported by this driver'));
        $ThumbCreator->resize(10)->save();
    }

    /**
     * Test for `getImageInstance()` method, with a not readable image
     * @test
     * @uses \Thumber\Utility\ThumbCreator::getImageInstance()
     */
    public function testGetImageInstanceNotReadableImageException(): void
    {
        $this->expectExceptionMessage('Unable to read image from file `anExampleFile`');
        $ThumbCreator = $this->getThumbCreatorInstance();
        $ThumbCreator->ImageManager = $this->createPartialMock(ImageManager::class, ['make']);
        $ThumbCreator->ImageManager->method('make')
            ->willThrowException(new LogicException('Unable to read image from file `anExampleFile`'));
        $ThumbCreator->resize(10)->save();
    }

    /**
     * @test
     * @uses \Thumber\Utility\ThumbCreator::getUrl()
     */
    public function testGetUrl(): void
    {
        $this->loadPlugins(['Thumber' => []]);

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
     * Test for `save()` method, with no valid method called before
     * @test
     * @uses \Thumber\Utility\ThumbCreator::save()
     */
    public function testSaveWithNoValidMethod(): void
    {
        $this->expectExceptionMessage('No valid method called before the `save()` method');
        $this->getThumbCreatorInstance()->save();
    }

    /**
     * Test for `save()` method, with a bad target
     * @test
     * @uses \Thumber\Utility\ThumbCreator::save()
     */
    public function testSaveWithBadTarget(): void
    {
        $this->skipIf(IS_WIN);
        $this->expectExceptionMessage('Unable to create file `' . DS . 'noExisting`');
        $this->getThumbCreatorInstance()->resize(200)->save(['target' => DS . 'noExisting']);
    }
}
