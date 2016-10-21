<?php
/**
 * This file is part of cakephp-thumber.
 *
 * cakephp-thumber is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * cakephp-thumber is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with cakephp-thumber.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */

namespace Thumber\Test\TestCase\Utility;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Thumber\TestSuite\TestCase;
use Thumber\Utility\ThumbCreator as BaseThumbCreator;

/**
 * Makes public some protected methods/properties from `ThumbCreator`
 */
class ThumbCreator extends BaseThumbCreator
{
    public function getExtension()
    {
        return $this->extension;
    }

    public function getPath()
    {
        return $this->path;
    }
}

/**
 * ThumbCreatorTest class
 */
class ThumbCreatorTest extends TestCase
{
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
     * @expectedExceptionMessage File `webroot/img/noExistingFile.gif` not readable
     * @test
     */
    public function testConstructNoExistingFile()
    {
        new ThumbCreator('noExistingFile.gif');
    }

    /**
     * Test for `__construct()` method, passing a no existing file from plugin
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage File `Plugin/TestPlugin/webroot/img/noExistingFile.gif` not readable
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
        $this->assertEquals($thumber->getExtension(), 'bmp');

        $thumber = new ThumbCreator('400x400.gif');
        $this->assertEquals($thumber->getExtension(), 'gif');

        $thumber = new ThumbCreator('400x400.ico');
        $this->assertEquals($thumber->getExtension(), 'ico');

        $thumber = new ThumbCreator('400x400.jpg');
        $this->assertEquals($thumber->getExtension(), 'jpg');

        $thumber = new ThumbCreator('400x400.jpeg');
        $this->assertEquals($thumber->getExtension(), 'jpg');

        $thumber = new ThumbCreator('400x400.png');
        $this->assertEquals($thumber->getExtension(), 'png');

        $thumber = new ThumbCreator('400x400.psd');
        $this->assertEquals($thumber->getExtension(), 'psd');

        $thumber = new ThumbCreator('400x400.tif');
        $this->assertEquals($thumber->getExtension(), 'tiff');

        $thumber = new ThumbCreator('400x400.tiff');
        $this->assertEquals($thumber->getExtension(), 'tiff');

        //Full path
        $thumber = new ThumbCreator(WWW_ROOT . 'img' . DS . '400x400.png');
        $this->assertEquals($thumber->getExtension(), 'png');

        //From plugin
        $thumber = new ThumbCreator('TestPlugin.400x400.png');
        $this->assertEquals($thumber->getExtension(), 'png');

        //From remote
        $thumber = new ThumbCreator('http://example.com.png');
        $this->assertEquals($thumber->getExtension(), 'png');

        $thumber = new ThumbCreator('http://example.com.png?');
        $this->assertEquals($thumber->getExtension(), 'png');

        $thumber = new ThumbCreator('http://example.com.png?param');
        $this->assertEquals($thumber->getExtension(), 'png');

        $thumber = new ThumbCreator('http://example.com.png?param=value');
        $this->assertEquals($thumber->getExtension(), 'png');
    }

    /**
     * Test for `$path` property
     * @ŧest
     */
    public function testPath()
    {
        $file = WWW_ROOT . 'img' . DS . '400x400.png';

        $thumber = new ThumbCreator('400x400.png');
        $this->assertEquals($thumber->getPath(), $file);

        $thumber = new ThumbCreator($file);
        $this->assertEquals($thumber->getPath(), $file);

        //From plugin
        $file = Plugin::path('TestPlugin') . 'webroot' . DS . 'img' . DS . '400x400.png';

        $thumber = new ThumbCreator('TestPlugin.400x400.png');
        $this->assertEquals($thumber->getPath(), $file);

        $thumber = new ThumbCreator($file);
        $this->assertEquals($thumber->getPath(), $file);

        //From remote
        $file = 'http://example.com.png';

        $thumber = new ThumbCreator($file);
        $this->assertEquals($thumber->getPath(), $file);
    }
}
