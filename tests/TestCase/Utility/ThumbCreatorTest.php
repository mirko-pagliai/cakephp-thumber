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
use Cake\Http\BaseApplication;
use Intervention\Image\Exception\NotReadableException;
use Thumber\TestSuite\TestCase;

/**
 * ThumbCreatorTest class
 */
class ThumbCreatorTest extends TestCase
{
    /**
     * Test for `__construct()` method, passing a no existing file
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /^File `[\w\/:\\\.]+` not readable$/
     * @test
     */
    public function testConstructNoExistingFile()
    {
        $this->getThumbCreatorInstance('noExistingFile.gif');
    }

    /**
     * Test for `__construct()` method, passing a no existing file from plugin
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /^File `[\w\/:\\\.]+` not readable$/
     * @test
     */
    public function testConstructNoExistingFileFromPlugin()
    {
        $this->getThumbCreatorInstance('TestPlugin.noExistingFile.gif');
    }

    /**
     * Test for `getImageInstance()` method, with unsupported image type for. GD driver
     * @expectedException RuntimeException
     * @expectedExceptionMessage Image type `image/jpeg` is not supported by this driver
     * @ŧest
     */
    public function testGetImageInstanceUnsupportedImageType()
    {
        $thumbCreator = $this->getThumbCreatorInstance();

        $thumbCreator->ImageManager = $this->getMockBuilder(get_class($thumbCreator->ImageManager))->getMock();
        $thumbCreator->ImageManager->method('make')->will($this->throwException(new NotReadableException(
            'Unsupported image type. GD driver is only able to decode JPG, PNG, GIF or WebP files.'
        )));

        $this->invokeMethod($thumbCreator, 'getImageInstance');
    }

    /**
     * Test for `getUrl()` method
     * @ŧest
     */
    public function testGetUrl()
    {
        //With full base
        $result = $this->getThumbCreatorInstanceWithSave()->getUrl();
        $this->assertThumbUrl($result);
        $this->assertTextStartsWith(Configure::read('App.fullBaseUrl'), $result);

        //Without full base
        $result = $this->getThumbCreatorInstanceWithSave()->getUrl(false);
        $this->assertThumbUrl($result);
        $this->assertTextStartsNotWith(Configure::read('App.fullBaseUrl'), $result);
    }

    /**
     * Test for `getUrl()` method, without the `$target` property
     * @expectedException InvalidArgumentException
     * @ŧest
     */
    public function testGetUrlMissingTarget()
    {
        $this->getThumbCreatorInstance()->getUrl();
    }

    /**
     * Test for `$path` property
     * @ŧest
     */
    public function testPath()
    {
        $app = $this->getMockForAbstractClass(BaseApplication::class, ['']);
        $app->addPlugin('TestPlugin')->pluginBootstrap();

        $file = WWW_ROOT . 'img' . DS . '400x400.jpg';
        $thumber = $this->getThumbCreatorInstance();
        $this->assertEquals($this->getProperty($thumber, 'path'), $file);

        $thumber = $this->getThumbCreatorInstance($file);
        $this->assertEquals($this->getProperty($thumber, 'path'), $file);

        //From plugin
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
