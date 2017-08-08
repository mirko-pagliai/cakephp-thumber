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
use Reflection\ReflectionTrait;
use Thumber\TestSuite\TestCase;
use Thumber\Utility\ThumbCreator;

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
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage File `tests/test_app/webroot/img/noExistingFile.gif` not readable
     * @test
     */
    public function testConstructNoExistingFile()
    {
        new ThumbCreator('noExistingFile.gif');
    }

    /**
     * Test for `__construct()` method, passing a no existing file from plugin
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage File `tests/test_app/Plugin/TestPlugin/webroot/img/noExistingFile.gif` not readable
     * @test
     */
    public function testConstructNoExistingFileFromPlugin()
    {
        new ThumbCreator('TestPlugin.noExistingFile.gif');
    }

    /**
     * Test for `$extension` property
     * @ŧest
     */
    public function testExtension()
    {
        $thumber = new ThumbCreator('400x400.bmp');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'bmp');

        $thumber = new ThumbCreator('400x400.gif');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'gif');

        $thumber = new ThumbCreator('400x400.ico');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'ico');

        $thumber = new ThumbCreator('400x400.jpg');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'jpg');

        $thumber = new ThumbCreator('400x400.jpeg');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'jpg');

        $thumber = new ThumbCreator('400x400.png');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'png');

        $thumber = new ThumbCreator('400x400.psd');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'psd');

        $thumber = new ThumbCreator('400x400.tif');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'tiff');

        $thumber = new ThumbCreator('400x400.tiff');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'tiff');

        //Full path
        $thumber = new ThumbCreator(WWW_ROOT . 'img' . DS . '400x400.png');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'png');

        //From plugin
        $thumber = new ThumbCreator('TestPlugin.400x400.png');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'png');

        //From remote
        $thumber = new ThumbCreator('http://example.com.png');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'png');

        $thumber = new ThumbCreator('http://example.com.png?');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'png');

        $thumber = new ThumbCreator('http://example.com.png?param');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'png');

        $thumber = new ThumbCreator('http://example.com.png?param=value');
        $this->assertEquals($this->getProperty($thumber, 'extension'), 'png');
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
