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

use Thumber\TestSuite\TestCase;
use Thumber\Utility\ThumbCreator;

/**
 * ThumbCreatorOperationsTest class
 */
class ThumbCreatorOperationsTest extends TestCase
{
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
        $this->assertImageFileEquals('crop_w200_h200.jpg', $thumb);

        //In this case, the width will be the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->crop(400, 200)->save();
        $this->assertImageFileEquals('crop_w400_h200.jpg', $thumb);
    }

    /**
     * Test for `crop()` method, using `x` and `y` options
     * @ŧest
     */
    public function testCropXAndY()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->crop(200, 200, ['x' => 50, 'y' => 50])->save();
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
        $thumb = (new ThumbCreator('400x400.jpg'))->crop(200, 200, ['x' => 50, 'y' => 50])->save();
        $this->assertImageFileEquals('crop_w200_h200_x50_y50.jpg', $thumb);
    }

    /**
     * Test for `crop()` method, called without parameters
     * @expectedException Intervention\Image\Exception\InvalidArgumentException
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
        $this->assertImageFileEquals('fit_w200_h200.jpg', $thumb);

        $thumb = (new ThumbCreator('example_pic.jpg'))->fit(200, 400)->save();
        $this->assertImageFileEquals('fit_w200_h400.jpg', $thumb);
    }

    /**
     * Test for `fit()` method, using `position` option
     * @ŧest
     */
    public function testFitPosition()
    {
        $thumb = (new ThumbCreator('example_pic.jpg'))->fit(200, 200, ['position' => 'top'])->save();
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
        $thumb = (new ThumbCreator('example_pic.jpg'))->fit(200, 200, ['position' => 'top-left'])->save();
        $this->assertImageFileEquals('fit_w200_h200_position_top_left.jpg', $thumb);

        $thumb = (new ThumbCreator('example_pic.jpg'))->fit(200, 200, ['position' => 'bottom-right'])->save();
        $this->assertImageFileEquals('fit_w200_h200_position_bottom_right.jpg', $thumb);
    }

    /**
     * Test for `fit()` method, using  the `upsize` option
     * @ŧest
     */
    public function testFitUpsize()
    {
        //In this case, the thumbnail will keep the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(450, 450, ['upsize' => true])->save();
        $this->assertImageSize($thumb, 400, 400);
        $this->assertMime($thumb, 'image/jpeg');

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(450, 450, ['upsize' => false])->save();
        $this->assertImageSize($thumb, 450, 450);
        $this->assertMime($thumb, 'image/jpeg');

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(null, 450, ['upsize' => false])->save();
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
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(450, 450, ['upsize' => true])->save();
        $this->assertImageFileEquals('fit_w400_h400.jpg', $thumb);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(450, 450, ['upsize' => false])->save();
        $this->assertImageFileEquals('fit_w450_h450_noUpsize.jpg', $thumb);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->fit(null, 450, ['upsize' => false])->save();
        $this->assertImageFileEquals('fit_w450_h450_noUpsize.jpg', $thumb);
    }

    /**
     * Test for `fit()` method, called without parameters
     * @expectedException Intervention\Image\Exception\InvalidArgumentException
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
        $this->assertImageFileEquals('resize_w200_h200.jpg', $thumb);

        $thumb = (new ThumbCreator('400x400.jpg'))->resize(null, 200)->save();
        $this->assertImageFileEquals('resize_w200_h200.jpg', $thumb);
    }

    /**
     * Test for `resize()` method, using  the `aspectRatio` option
     * @ŧest
     */
    public function testResizeAspectRatio()
    {
        //In this case, the thumbnail will keep the ratio
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200, 300, ['aspectRatio' => true])->save();
        $this->assertImageSize($thumb, 200, 200);
        $this->assertMime($thumb, 'image/jpeg');

        //In this case, the thumbnail will not maintain the ratio
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200, 300, ['aspectRatio' => false])->save();
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
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200, 300, ['aspectRatio' => true])->save();
        $this->assertImageFileEquals('resize_w200_h200.jpg', $thumb);

        //In this case, the thumbnail will not maintain the ratio
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200, 300, ['aspectRatio' => false])->save();
        $this->assertImageFileEquals('resize_w200_h300_noAspectRatio.jpg', $thumb);
    }

    /**
     * Test for `resize()` method, using  the `upsize` option
     * @ŧest
     */
    public function testResizeUpsize()
    {
        //In this case, the thumbnail will keep the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(450, 450, ['upsize' => true])->save();
        $this->assertImageSize($thumb, 400, 400);
        $this->assertMime($thumb, 'image/jpeg');

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(450, 450, ['upsize' => false])->save();
        $this->assertImageSize($thumb, 450, 450);
        $this->assertMime($thumb, 'image/jpeg');

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(null, 450, ['upsize' => false])->save();
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
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(450, 450, ['upsize' => true])->save();
        $this->assertImageFileEquals('resize_w400_h400.jpg', $thumb);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(450, 450, ['upsize' => false])->save();
        $this->assertImageFileEquals('resize_w450_h450_noUpsize.jpg', $thumb);

        //In this case, the thumbnail will exceed the original size
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(null, 450, ['upsize' => false])->save();
        $this->assertImageFileEquals('resize_w450_h450_noUpsize.jpg', $thumb);
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
        $this->assertImageFileEquals('resize_w400_h400.jpg', $thumb);

        //In this case, the thumbnail will not keep the ratio and the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(500, 600, [
            'aspectRatio' => false,
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals('resize_w500_h600_noAspectRatio_noUpsize.jpg', $thumb);

        //In this case, the thumbnail will not keep the ratio and the original dimensions
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(null, 600, [
            'aspectRatio' => false,
            'upsize' => false,
        ])->save();
        $this->assertImageFileEquals('resize_w400_h600_noAspectRatio_noUpsize.jpg', $thumb);
    }

    /**
     * Test for `resize()` method, called without parameters
     * @expectedException Intervention\Image\Exception\InvalidArgumentException
     * @test
     */
    public function testResizeWithoutParameters()
    {
        (new ThumbCreator('400x400.gif'))->resize()->save();
    }

    /**
     * Test for `resizeCanvas()` method
     * @ŧest
     */
    public function testResizeCanvas()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resizeCanvas(200, 100)->save();
        $this->assertImageSize($thumb, 200, 100);
        $this->assertMime($thumb, 'image/jpeg');

        $thumb = (new ThumbCreator('400x400.jpg'))->resizeCanvas(null, 200)->save();
        $this->assertImageSize($thumb, 400, 200);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `resizeCanvas()` method, equating images
     * @group imageEquals
     * @test
     */
    public function testResizeCanvasImageEquals()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resizeCanvas(300, 200)->save();
        $this->assertImageFileEquals('resize_canvas_w300_h200.jpg', $thumb);

        $thumb = (new ThumbCreator('400x400.jpg'))->resizeCanvas(null, 100)->save();
        $this->assertImageFileEquals('resize_canvas_w400_h100.jpg', $thumb);
    }

    /**
     * Test for `resizeCanvas()` method, using  the `anchor` option
     * @ŧest
     */
    public function testResizeCanvasAnchor()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resizeCanvas(300, 300, ['anchor' => 'bottom'])->save();
        $this->assertImageSize($thumb, 300, 300);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `resizeCanvas()` method, using  the `anchor` option, equating images
     * @group imageEquals
     * @test
     */
    public function testResizeCanvasAnchorImageEquals()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resizeCanvas(300, 300, ['anchor' => 'bottom'])->save();
        $this->assertImageFileEquals('resize_canvas_w300_h300_anchor_bottom.jpg', $thumb);
    }

    /**
     * Test for `resizeCanvas()` method, using  the `relative` and `bgcolor` options
     * @ŧest
     */
    public function testResizeCanvasRelativeAndBgcolor()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resizeCanvas(300, 300, ['relative' => true, 'bgcolor' => '#000000'])->save();
        $this->assertImageSize($thumb, 700, 700);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `resizeCanvas()` method, using  the `relative` and `bgcolor` options, equating images
     * @group imageEquals
     * @test
     */
    public function testResizeCanvasRelativeAndBgcolorImageEquals()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resizeCanvas(300, 300, ['relative' => true, 'bgcolor' => '#000000'])->save();
        $this->assertImageFileEquals('resize_canvas_w700_h700_relative_and_black.jpg', $thumb);
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
        $this->assertImageFileEquals('crop_and_resize_w600_h200.jpg', $thumb);
    }
}
