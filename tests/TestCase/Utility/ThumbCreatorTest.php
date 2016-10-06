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
use Cake\TestSuite\TestCase;
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
 * ThumbCreatorTest class.
 *
 * Some tests use remote files (`remote-file` group tag).
 * To exclude these tests, you can use `phpunit --exclude-group remote-file`.
 */
class ThumbCreatorTest extends TestCase
{
    /**
     * Regex for thumbnails path
     * @var string
     */
    protected $thumbsPathRegex;

    /**
     * Construct
     * @uses $thumbsPathRegex
     */
    public function __construct()
    {
        parent::__construct();

        $this->thumbsPathRegex = sprintf('/^%s[a-z0-9]{32}\.png$/', preg_quote(Configure::read('Thumbs.target') . DS, '/'));
    }

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

        //Deletes all thumbnails
        foreach (glob(Configure::read('Thumbs.target') . DS . '*') as $file) {
            unlink($file);
        }
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
        $thumber = new ThumbCreator('400x400.png');
        $this->assertEquals($thumber->getExtension(), 'png');

        $thumber = new ThumbCreator(WWW_ROOT . 'img' . DS . '400x400.png');
        $this->assertEquals($thumber->getExtension(), 'png');

        $thumber = new ThumbCreator('400x400.gif');
        $this->assertEquals($thumber->getExtension(), 'gif');

        $thumber = new ThumbCreator('400x400.jpg');
        $this->assertEquals($thumber->getExtension(), 'jpg');

        $thumber = new ThumbCreator('400x400.jpeg');
        $this->assertEquals($thumber->getExtension(), 'jpeg');

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

    /**
     * Test for `crop()` method
     * @ŧest
     * @uses $thumbsPathRegex
     */
    public function testCrop()
    {
        $thumb = (new ThumbCreator('400x400.png'))->crop(200, 200)->save();
        $this->assertFileExists($thumb);
        $this->assertRegExp($this->thumbsPathRegex, $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 200);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 200);
        $this->assertEquals(array_values(getimagesize($thumb))[5], 'image/png');

        //In this case, the width will be the original size
        $thumb = (new ThumbCreator('400x400.png'))->crop(500, 200)->save();
        $this->assertFileExists($thumb);
        $this->assertRegExp($this->thumbsPathRegex, $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 400);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 200);
        $this->assertEquals(array_values(getimagesize($thumb))[5], 'image/png');
    }

    /**
     * Test for `crop()` method, using  `x` and `y` options
     * @ŧest
     * @uses $thumbsPathRegex
     */
    public function testCropXAndY()
    {
        $thumb = (new ThumbCreator('400x400.png'))->crop(200, 200, [
            'x' => 50,
            'y' => 50,
        ])->save();
        $this->assertFileExists($thumb);
        $this->assertRegExp($this->thumbsPathRegex, $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 200);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 200);
        $this->assertEquals(array_values(getimagesize($thumb))[5], 'image/png');
    }

    /**
     * Test for `resize()` method
     * @ŧest
     * @uses $thumbsPathRegex
     */
    public function testResize()
    {
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save();
        $this->assertFileExists($thumb);
        $this->assertRegExp($this->thumbsPathRegex, $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 200);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 200);
        $this->assertEquals(array_values(getimagesize($thumb))[5], 'image/png');

        $thumb = (new ThumbCreator('400x400.png'))->resize(null, 200)->save();
        $this->assertFileExists($thumb);
        $this->assertRegExp($this->thumbsPathRegex, $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 200);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 200);
        $this->assertEquals(array_values(getimagesize($thumb))[5], 'image/png');
    }

    /**
     * Test for `resize()` method, using  the `aspectRatio` option
     * @ŧest
     * @uses $thumbsPathRegex
     */
    public function testResizeAspectRatio()
    {
        //In this case, the thumbnail will keep the ratio
        $thumb = (new ThumbCreator('400x400.png'))->resize(200, 300, [
            'aspectRatio' => true,
        ])->save();
        $this->assertFileExists($thumb);
        $this->assertRegExp($this->thumbsPathRegex, $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 200);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 200);

        //In this case, the thumbnail will not maintain the ratio
        $thumb = (new ThumbCreator('400x400.png'))->resize(200, 300, [
            'aspectRatio' => false,
        ])->save();
        $this->assertFileExists($thumb);
        $this->assertRegExp($this->thumbsPathRegex, $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 200);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 300);
    }

    /**
     * Test for `resize()` method, using  the `upsize` option
     * @ŧest
     * @uses $thumbsPathRegex
     */
    public function testResizeUpsize()
    {
        //In this case, the thumbnail will keep the original dimensions
        $thumb = (new ThumbCreator('400x400.png'))->resize(450, 450, [
            'upsize' => true,
        ])->save();
        $this->assertFileExists($thumb);
        $this->assertRegExp($this->thumbsPathRegex, $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 400);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 400);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.png'))->resize(450, 450, [
            'upsize' => false,
        ])->save();
        $this->assertFileExists($thumb);
        $this->assertRegExp($this->thumbsPathRegex, $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 450);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 450);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.png'))->resize(null, 450, [
            'upsize' => false,
        ])->save();
        $this->assertFileExists($thumb);
        $this->assertRegExp($this->thumbsPathRegex, $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 450);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 450);
    }

    /**
     * Test for `resize()` method, using  `aspectRatio` and `upsize` options
     * @ŧest
     * @uses $thumbsPathRegex
     */
    public function testResizeAspectRatioAndUpsize()
    {
        //In this case, the thumbnail will keep the ratio and the original dimensions
        $thumb = (new ThumbCreator('400x400.png'))->resize(500, 600, [
            'aspectRatio' => true,
            'upsize' => true,
        ])->save();
        $this->assertFileExists($thumb);
        $this->assertRegExp($this->thumbsPathRegex, $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 400);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 400);

        //In this case, the thumbnail will not keep the ratio and the original dimensions
        $thumb = (new ThumbCreator('400x400.png'))->resize(500, 600, [
            'aspectRatio' => false,
            'upsize' => false,
        ])->save();
        $this->assertFileExists($thumb);
        $this->assertRegExp($this->thumbsPathRegex, $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 500);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 600);

        //In this case, the thumbnail will not keep the ratio and the original dimensions
        $thumb = (new ThumbCreator('400x400.png'))->resize(null, 600, [
            'aspectRatio' => false,
            'upsize' => false,
        ])->save();
        $this->assertFileExists($thumb);
        $this->assertRegExp($this->thumbsPathRegex, $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 400);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 600);
    }

    /**
     * Test for `save()` method, passing a no existing directory target
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Can't write the file `/tmp/noExistingDir/thumb.png`
     * @test
     */
    public function testSaveNoExistingDir()
    {
        (new ThumbCreator('400x400.png'))->resize(200)
            ->save(TMP . 'noExistingDir' . DS . 'thumb.png');
    }

    /**
     * Test for `save()` method, using  a custom target path
     * @ŧest
     */
    public function testSaveWithCustomTarget()
    {
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)
            ->save(Configure::read('Thumbs.target') . DS . 'thumb.png');
        $this->assertFileExists($thumb);
        $this->assertEquals(Configure::read('Thumbs.target') . DS . 'thumb.png', $thumb);
        $this->assertEquals(array_values(getimagesize($thumb))[0], 200);
        $this->assertEquals(array_values(getimagesize($thumb))[1], 200);
        $this->assertEquals(array_values(getimagesize($thumb))[5], 'image/png');
    }
}
