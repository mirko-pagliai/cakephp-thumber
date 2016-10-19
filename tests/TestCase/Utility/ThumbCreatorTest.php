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

    /**
     * Test for `crop()` method
     * @ŧest
     */
    public function testCrop()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->crop(200, 200)->save();
        $this->assertImageSize($thumb, 200, 200);
        $this->assertMime($thumb, 'image/jpeg');

        //Only width
        $thumb = (new ThumbCreator('400x400.jpg'))->crop(200)->save();
        $this->assertImageSize($thumb, 200, 200);
        $this->assertMime($thumb, 'image/jpeg');

        //In this case, the width will be the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->crop(400, 200)->save();
        $this->assertImageSize($thumb, 400, 200);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `crop()` method, equating images
     * @group imageEquals
     * @test
     */
    public function testCropImageEquals()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->crop(200, 200)->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'crop_w200_h200.jpg', $thumb);

        //In this case, the width will be the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->crop(400, 200)->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'crop_w400_h200.jpg', $thumb);
    }

    /**
     * Test for `crop()` method, using `x` and `y` options
     * @ŧest
     */
    public function testCropXAndY()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->crop(200, 200, [
            'x' => 50,
            'y' => 50,
        ])->save();
        $this->assertImageSize($thumb, 200, 200);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `crop()` method, using  `x` and `y` options, equating images
     * @group imageEquals
     * @test
     */
    public function testCropXAndYImageEquals()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->crop(200, 200, [
            'x' => 50,
            'y' => 50,
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'crop_w200_h200_x50_y50.jpg', $thumb);
    }

    /**
     * Test for `crop()` method, called without parameters
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Missing parameters for the `crop` method
     * @test
     */
    public function testCropWithoutParameters()
    {
        (new ThumbCreator('400x400.gif'))->crop()->save();
    }

    /**
     * Test for `fit()` method
     * @ŧest
     */
    public function testFit()
    {
        $thumb = (new ThumbCreator('example_pic.jpg'))->fit(200)->save();
        $this->assertImageSize($thumb, 200, 200);
        $this->assertMime($thumb, 'image/jpeg');

        $thumb = (new ThumbCreator('example_pic.jpg'))->fit(200, 400)->save();
        $this->assertImageSize($thumb, 200, 400);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `fit()` method, equating images
     * @group imageEquals
     * @test
     */
    public function testFitImageEquals()
    {
        $thumb = (new ThumbCreator('example_pic.jpg'))->fit(200)->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'fit_w200_h200.jpg', $thumb);

        $thumb = (new ThumbCreator('example_pic.jpg'))->fit(200, 400)->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'fit_w200_h400.jpg', $thumb);
    }

    /**
     * Test for `fit()` method, using `position` option
     * @ŧest
     */
    public function testFitPosition()
    {
        $thumb = (new ThumbCreator('example_pic.jpg'))->fit(200, 200, [
            'position' => 'top',
        ])->save();
        $this->assertImageSize($thumb, 200, 200);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `fit()` method, using `position` option, equating images
     * @group imageEquals
     * @ŧest
     */
    public function testFitPositionImageEquals()
    {
        $thumb = (new ThumbCreator('example_pic.jpg'))->fit(200, 200, [
            'position' => 'top-left',
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'fit_w200_h200_position_top_left.jpg', $thumb);

        $thumb = (new ThumbCreator('example_pic.jpg'))->fit(200, 200, [
            'position' => 'bottom-right',
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'fit_w200_h200_position_bottom_right.jpg', $thumb);
    }

    /**
     * Test for `fit()` method, using  the `upsize` option
     * @ŧest
     */
    public function testFitUpsize()
    {
        //In this case, the thumbnail will keep the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(450, 450, [
            'upsize' => true,
        ])->save();
        $this->assertImageSize($thumb, 400, 400);
        $this->assertMime($thumb, 'image/jpeg');

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(450, 450, [
            'upsize' => false,
        ])->save();
        $this->assertImageSize($thumb, 450, 450);
        $this->assertMime($thumb, 'image/jpeg');

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(null, 450, [
            'upsize' => false,
        ])->save();
        $this->assertImageSize($thumb, 450, 450);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `resize()` method, using  the `upsize` option, equating images
     * @group imageEquals
     * @test
     */
    public function testFitUpsizeImageEquals()
    {
        //In this case, the thumbnail will keep the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(450, 450, [
            'upsize' => true,
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'fit_w400_h400.jpg', $thumb);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(450, 450, [
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'fit_w450_h450_noUpsize.jpg', $thumb);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(null, 450, [
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'fit_w450_h450_noUpsize.jpg', $thumb);
    }

    /**
     * Test for `fit()` method, called without parameters
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Missing parameters for the `fit` method
     * @test
     */
    public function testFitWithoutParameters()
    {
        (new ThumbCreator('400x400.gif'))->fit()->save();
    }

    /**
     * Test for `resize()` method
     * @ŧest
     */
    public function testResize()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200)->save();
        $this->assertImageSize($thumb, 200, 200);
        $this->assertMime($thumb, 'image/jpeg');

        $thumb = (new ThumbCreator('400x400.jpg'))->resize(null, 200)->save();
        $this->assertImageSize($thumb, 200, 200);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `resize()` method, equating images
     * @group imageEquals
     * @test
     */
    public function testResizeImageEquals()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200)->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'resize_w200_h200.jpg', $thumb);

        $thumb = (new ThumbCreator('400x400.jpg'))->resize(null, 200)->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'resize_w200_h200.jpg', $thumb);
    }

    /**
     * Test for `resize()` method, using  the `aspectRatio` option
     * @ŧest
     */
    public function testResizeAspectRatio()
    {
        //In this case, the thumbnail will keep the ratio
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200, 300, [
            'aspectRatio' => true,
        ])->save();
        $this->assertImageSize($thumb, 200, 200);
        $this->assertMime($thumb, 'image/jpeg');

        //In this case, the thumbnail will not maintain the ratio
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200, 300, [
            'aspectRatio' => false,
        ])->save();
        $this->assertImageSize($thumb, 200, 300);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `resize()` method, using  the `aspectRatio` option, equating
     *  images
     * @group imageEquals
     * @test
     */
    public function testResizeAspectRatioImageEquals()
    {
        //In this case, the thumbnail will keep the ratio
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200, 300, [
            'aspectRatio' => true,
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'resize_w200_h200.jpg', $thumb);

        //In this case, the thumbnail will not maintain the ratio
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200, 300, [
            'aspectRatio' => false,
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'resize_w200_h300_noAspectRatio.jpg', $thumb);
    }

    /**
     * Test for `resize()` method, using  the `upsize` option
     * @ŧest
     */
    public function testResizeUpsize()
    {
        //In this case, the thumbnail will keep the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(450, 450, [
            'upsize' => true,
        ])->save();
        $this->assertImageSize($thumb, 400, 400);
        $this->assertMime($thumb, 'image/jpeg');

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(450, 450, [
            'upsize' => false,
        ])->save();
        $this->assertImageSize($thumb, 450, 450);
        $this->assertMime($thumb, 'image/jpeg');

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(null, 450, [
            'upsize' => false,
        ])->save();
        $this->assertImageSize($thumb, 450, 450);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `resize()` method, using  the `upsize` option, equating images
     * @group imageEquals
     * @test
     */
    public function testResizeUpsizeImageEquals()
    {
        //In this case, the thumbnail will keep the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(450, 450, [
            'upsize' => true,
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'resize_w400_h400.jpg', $thumb);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(450, 450, [
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'resize_w450_h450_noUpsize.jpg', $thumb);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(null, 450, [
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'resize_w450_h450_noUpsize.jpg', $thumb);
    }

    /**
     * Test for `resize()` method, using  `aspectRatio` and `upsize` options
     * @ŧest
     */
    public function testResizeAspectRatioAndUpsize()
    {
        //In this case, the thumbnail will keep the ratio and the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(500, 600, [
            'aspectRatio' => true,
            'upsize' => true,
        ])->save();
        $this->assertImageSize($thumb, 400, 400);
        $this->assertMime($thumb, 'image/jpeg');

        //In this case, the thumbnail will not keep the ratio and the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(500, 600, [
            'aspectRatio' => false,
            'upsize' => false,
        ])->save();
        $this->assertImageSize($thumb, 500, 600);
        $this->assertMime($thumb, 'image/jpeg');

        //In this case, the thumbnail will not keep the ratio and the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(null, 600, [
            'aspectRatio' => false,
            'upsize' => false,
        ])->save();
        $this->assertImageSize($thumb, 400, 600);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `resize()` method, using  `aspectRatio` and `upsize` options,
     *  equating images
     * @group imageEquals
     * @test
     */
    public function testResizeAspectRatioAndUpsizeImageEquals()
    {
        //In this case, the thumbnail will keep the ratio and the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(500, 600, [
            'aspectRatio' => true,
            'upsize' => true,
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'resize_w400_h400.jpg', $thumb);

        //In this case, the thumbnail will not keep the ratio and the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(500, 600, [
            'aspectRatio' => false,
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'resize_w500_h600_noAspectRatio_noUpsize.jpg', $thumb);

        //In this case, the thumbnail will not keep the ratio and the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(null, 600, [
            'aspectRatio' => false,
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'resize_w400_h600_noAspectRatio_noUpsize.jpg', $thumb);
    }

    /**
     * Test for `resize()` method, called without parameters
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Missing parameters for the `resize` method
     * @test
     */
    public function testResizeWithoutParameters()
    {
        (new ThumbCreator('400x400.gif'))->resize()->save();
    }

    /**
     * Test for several methods called in sequence on the same image (eg.,
     *  `crop()` and `resize()`
     * @test
     */
    public function testSeveralMethods()
    {
        $thumb = (new ThumbCreator('example_pic.jpg'))->crop(600)->resize(200)->save();
        $this->assertImageSize($thumb, 200, 200);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for several methods called in sequence on the same image (eg.,
     *  `crop()` and `resize()`, equating images
     * @group imageEquals
     * @test
     */
    public function testSeveralMethodsImageEquals()
    {
        $thumb = (new ThumbCreator('example_pic.jpg'))->crop(600)->resize(200)->save();
        $this->assertImageFileEquals(COMPARING_DIR . 'crop_and_resize_w600_h200.jpg', $thumb);
    }

    /**
     * Test for `save()` method, passing a no existing directory target
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Can't write the file `/tmp/noExistingDir/thumb.jpg`
     * @test
     */
    public function testSaveNoExistingDir()
    {
        (new ThumbCreator('400x400.png'))->resize(200)
            ->save(['target' => TMP . 'noExistingDir' . DS . 'thumb.jpg']);
    }

    /**
     * Test for `save()` method. It tests the thumbnails is created only if it
     *  does not exist
     * @test
     */
    public function testSaveReturnsExistingThumb()
    {
        //Creates the thumbnail and gets the creation time
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save();
        $time = filemtime($thumb);

        //Tries to create again the same thumbnail. Now the creation time is the same
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save();
        $this->assertEquals($time, filemtime($thumb));

        //Deletes the thumbnail and wait 1 second
        unlink($thumb);
        sleep(1);

        //Tries to create again the same thumbnail. Now the creation time is different
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save();
        $this->assertNotEquals($time, filemtime($thumb));
    }

    /**
     * Test for `save()` method, using the `format` option with an invalid file
     *  format
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Invalid `txt` format
     * @ŧest
     */
    public function testSaveWithInvalidFormat()
    {
        (new ThumbCreator('400x400.png'))->resize(200)->save(['format' => 'txt']);
    }

    /**
     * Test for `save()` method, using the `quality` option
     * @ŧest
     */
    public function testSaveWithQuality()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200)->save(['quality' => 10]);
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.jpg/', preg_quote(Configure::read('Thumbs.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `save()` method, using the `quality` option, equating images
     * @group imageEquals
     * @ŧest
     */
    public function testSaveWithQualityImageEquals()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200)->save(['quality' => 10]);
        $this->assertImageFileEquals(COMPARING_DIR . 'resize_w200_h200_quality_10.jpg', $thumb);
    }

    /**
     * Test for `save()` method, using the `target` option
     * @ŧest
     */
    public function testSaveWithTarget()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200)->save(['target' => 'thumb.jpg']);
        $this->assertEquals(Configure::read('Thumbs.target') . DS . 'thumb.jpg', $thumb);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `save()` method, using the `target` option with an invalid file
     *  format
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Invalid `txt` format
     * @test
     */
    public function testSaveWithInvalidTarget()
    {
        (new ThumbCreator('400x400.png'))->resize(200)->save(['target' => 'image.txt']);
    }

    /**
     * Test for `save()` method, without a valid method called before
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage No valid method called before the `save` method
     */
    public function testSaveWithoutCallbacks()
    {
        (new ThumbCreator('400x400.jpg'))->save();
    }
}
