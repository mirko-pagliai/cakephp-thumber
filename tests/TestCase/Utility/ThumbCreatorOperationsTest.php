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
use Thumber\TestSuite\TestCase;
use Thumber\Utility\ThumbCreator;

/**
 * ThumbCreatorOperationsTest class
 */
class ThumbCreatorOperationsTest extends TestCase
{
    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        //Deletes all thumbnails
        foreach (glob(Configure::read('Thumbs.target') . DS . '*') as $file) {
            unlink($file);
        }
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
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'crop_w200_h200.jpg', $thumb);

        //In this case, the width will be the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->crop(400, 200)->save();
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'crop_w400_h200.jpg', $thumb);
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
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'crop_w200_h200_x50_y50.jpg', $thumb);
    }

    /**
     * Test for `crop()` method, called without parameters
     * @expectedException Intervention\Image\Exception\InvalidArgumentException
     * @expectedExceptionMessage Width and height of cutout needs to be defined
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
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'fit_w200_h200.jpg', $thumb);

        $thumb = (new ThumbCreator('example_pic.jpg'))->fit(200, 400)->save();
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'fit_w200_h400.jpg', $thumb);
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
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'fit_w200_h200_position_top_left.jpg', $thumb);

        $thumb = (new ThumbCreator('example_pic.jpg'))->fit(200, 200, [
            'position' => 'bottom-right',
        ])->save();
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'fit_w200_h200_position_bottom_right.jpg', $thumb);
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
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'fit_w400_h400.jpg', $thumb);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(450, 450, [
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'fit_w450_h450_noUpsize.jpg', $thumb);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(null, 450, [
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'fit_w450_h450_noUpsize.jpg', $thumb);
    }

    /**
     * Test for `fit()` method, called without parameters
     * @expectedException Intervention\Image\Exception\InvalidArgumentException
     * @expectedExceptionMessage Width or height needs to be defined
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
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'resize_w200_h200.jpg', $thumb);

        $thumb = (new ThumbCreator('400x400.jpg'))->resize(null, 200)->save();
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'resize_w200_h200.jpg', $thumb);
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
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'resize_w200_h200.jpg', $thumb);

        //In this case, the thumbnail will not maintain the ratio
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200, 300, [
            'aspectRatio' => false,
        ])->save();
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'resize_w200_h300_noAspectRatio.jpg', $thumb);
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
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'resize_w400_h400.jpg', $thumb);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(450, 450, [
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'resize_w450_h450_noUpsize.jpg', $thumb);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(null, 450, [
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'resize_w450_h450_noUpsize.jpg', $thumb);
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
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'resize_w400_h400.jpg', $thumb);

        //In this case, the thumbnail will not keep the ratio and the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(500, 600, [
            'aspectRatio' => false,
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'resize_w500_h600_noAspectRatio_noUpsize.jpg', $thumb);

        //In this case, the thumbnail will not keep the ratio and the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(null, 600, [
            'aspectRatio' => false,
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'resize_w400_h600_noAspectRatio_noUpsize.jpg', $thumb);
    }

    /**
     * Test for `resize()` method, called without parameters
     * @expectedException Intervention\Image\Exception\InvalidArgumentException
     * @expectedExceptionMessage Width or height needs to be defined
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
        $this->assertImageFileEquals(Configure::read('Thumbs.comparingDir') . 'crop_and_resize_w600_h200.jpg', $thumb);
    }
}
