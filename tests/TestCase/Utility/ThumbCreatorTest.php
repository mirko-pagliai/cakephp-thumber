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

use Cake\Core\Plugin;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\ImageManager;
use Thumber\TestSuite\TestCase;
use Thumber\Utility\ThumbCreator;
use Tools\ReflectionTrait;

/**
 * ThumbCreatorTest class
 */
class ThumbCreatorTest extends TestCase
{
    use ReflectionTrait;

    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        Plugin::load('TestPlugin');
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        Plugin::unload('TestPlugin');
    }

    /**
     * Test for `__construct()` method, passing a no existing file
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /^File `[\w\/:\\\.]+` not readable$/
     * @test
     */
    public function testConstructNoExistingFile()
    {
        new ThumbCreator('noExistingFile.gif');
    }

    /**
     * Test for `__construct()` method, passing a no existing file from plugin
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /^File `[\w\/:\\\.]+` not readable$/
     * @test
     */
    public function testConstructNoExistingFileFromPlugin()
    {
        new ThumbCreator('TestPlugin.noExistingFile.gif');
    }

    /**
     * Test for `getImageInstance()` method, with unsupported image type for. GD driver
     * @expectedException RuntimeException
     * @expectedExceptionMessage Image type `image/png` is not supported by this driver
     * @ŧest
     */
    public function testGetImageInstanceUnsupportedImageType()
    {
        $thumber = new ThumbCreator('400x400.png');

        $message = 'Unsupported image type. GD driver is only able to decode JPG, PNG, GIF or WebP files.';
        $thumber->ImageManager = $this->getMockBuilder(ImageManager::class)->getMock();
        $thumber->ImageManager->method('make')->will($this->throwException(new NotReadableException($message)));

        $this->invokeMethod($thumber, 'getImageInstance');
    }

    /**
     * Test for `$path` property
     * @ŧest
     */
    public function testPath()
    {
        $file = WWW_ROOT . 'img' . DS . '400x400.png';

        $thumber = new ThumbCreator('400x400.png');
        $this->assertEquals($this->getProperty($thumber, 'path'), $file);

        $thumber = new ThumbCreator($file);
        $this->assertEquals($this->getProperty($thumber, 'path'), $file);

        //From plugin
        $file = Plugin::path('TestPlugin') . 'webroot' . DS . 'img' . DS . '400x400.png';

        $thumber = new ThumbCreator('TestPlugin.400x400.png');
        $this->assertEquals($this->getProperty($thumber, 'path'), $file);

        $thumber = new ThumbCreator($file);
        $this->assertEquals($this->getProperty($thumber, 'path'), $file);

        //From remote
        $file = 'http://example.com.png';

        $thumber = new ThumbCreator($file);
        $this->assertEquals($this->getProperty($thumber, 'path'), $file);
    }
}
