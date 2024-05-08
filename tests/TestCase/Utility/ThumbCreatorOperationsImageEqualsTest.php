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
namespace Thumber\Cake\Test\TestCase\Utility;

use Thumber\Cake\TestSuite\TestCase;

/**
 * ThumbCreatorOperationsImageEqualsTest class
 * @uses \Thumber\Cake\Utility\ThumbCreator
 */
class ThumbCreatorOperationsImageEqualsTest extends TestCase
{
    /**
     * @group imageEquals
     * @uses \Thumber\Cake\Utility\ThumbCreator::crop()
     * @test
     */
    public function testCrop(): void
    {
        $thumb = $this->getThumbCreatorInstance()->crop(200, 200)->save();
        $this->assertImageFileEquals('crop_w200_h200.jpg', $thumb);

        //In this case, the width will be the original size
        $thumb = $this->getThumbCreatorInstance()->crop(400, 200)->save();
        $this->assertImageFileEquals('crop_w400_h200.jpg', $thumb);

        //Using  `x` and `y` options
        $thumb = $this->getThumbCreatorInstance()->crop(200, 200, ['x' => 50, 'y' => 50])->save();
        $this->assertImageFileEquals('crop_w200_h200_x50_y50.jpg', $thumb);
    }

    /**
     * @group imageEquals
     * @uses \Thumber\Cake\Utility\ThumbCreator::fit()
     * @test
     */
    public function testFit(): void
    {
        $thumb = $this->getThumbCreatorInstance('example_pic.jpg')->fit(200)->save();
        $this->assertImageFileEquals('fit_w200_h200.jpg', $thumb);

        $thumb = $this->getThumbCreatorInstance('example_pic.jpg')->fit(200, 400)->save();
        $this->assertImageFileEquals('fit_w200_h400.jpg', $thumb);

        //Using `position` option
        $thumb = $this->getThumbCreatorInstance('example_pic.jpg')
            ->fit(200, 200, ['position' => 'top-left'])
            ->save();
        $this->assertImageFileEquals('fit_w200_h200_position_top_left.jpg', $thumb);

        $thumb = $this->getThumbCreatorInstance('example_pic.jpg')
            ->fit(200, 200, ['position' => 'bottom-right'])
            ->save();
        $this->assertImageFileEquals('fit_w200_h200_position_bottom_right.jpg', $thumb);

        //Using the `upsize` option
        //In this case, the thumbnail will keep the original dimensions
        $thumb = $this->getThumbCreatorInstance()->fit(450, 450, ['upsize' => true])->save();
        $this->assertImageFileEquals('fit_w400_h400.jpg', $thumb);

        //Using the `upsize` option
        //In this case, the thumbnail will exceed the original size
        $thumb = $this->getThumbCreatorInstance()->fit(450, 450, ['upsize' => false])->save();
        $this->assertImageFileEquals('fit_w450_h450_noUpsize.jpg', $thumb);
    }

    /**
     * @group imageEquals
     * @uses \Thumber\Cake\Utility\ThumbCreator::resize()
     * @test
     */
    public function testResize(): void
    {
        $thumb = $this->getThumbCreatorInstance()->resize(200)->save();
        $this->assertImageFileEquals('resize_w200_h200.jpg', $thumb);

        //Using the `aspectRatio` option
        //In this case, the thumbnail will keep the ratio
        $thumb = $this->getThumbCreatorInstance()->resize(200, 300, ['aspectRatio' => true])->save();
        $this->assertImageFileEquals('resize_w200_h200.jpg', $thumb);

        //Using the `aspectRatio` option
        //In this case, the thumbnail will not maintain the ratio
        $thumb = $this->getThumbCreatorInstance()->resize(200, 300, ['aspectRatio' => false])->save();
        $this->assertImageFileEquals('resize_w200_h300_noAspectRatio.jpg', $thumb);

        //Using the `upsize` option
        //In this case, the thumbnail will keep the original dimensions
        $thumb = $this->getThumbCreatorInstance()->resize(450, 450, ['upsize' => true])->save();
        $this->assertImageFileEquals('resize_w400_h400.jpg', $thumb);

        //Using the `upsize` option
        //In this case, the thumbnail will exceed the original size
        $thumb = $this->getThumbCreatorInstance()->resize(450, 450, ['upsize' => false])->save();
        $this->assertImageFileEquals('resize_w450_h450_noUpsize.jpg', $thumb);

        //Using `aspectRatio` and `upsize` options
        //In this case, the thumbnail will keep the ratio and the original dimensions
        $thumb = $this->getThumbCreatorInstance()->resize(500, 600, ['aspectRatio' => true, 'upsize' => true])->save();
        $this->assertImageFileEquals('resize_w400_h400.jpg', $thumb);

        //Using `aspectRatio` and `upsize` options
        //In this case, the thumbnail will not keep the ratio and the original dimensions
        $thumb = $this->getThumbCreatorInstance()->resize(500, 600, ['aspectRatio' => false, 'upsize' => false])->save();
        $this->assertImageFileEquals('resize_w500_h600_noAspectRatio_noUpsize.jpg', $thumb);
    }

    /**
     * @group imageEquals
     * @uses \Thumber\Cake\Utility\ThumbCreator::resizeCanvas()
     * @test
     */
    public function testResizeCanvas(): void
    {
        $thumb = $this->getThumbCreatorInstance()->resizeCanvas(300, 200)->save();
        $this->assertImageFileEquals('resize_canvas_w300_h200.jpg', $thumb);

        $thumb = $this->getThumbCreatorInstance()->resizeCanvas(400, 100)->save();
        $this->assertImageFileEquals('resize_canvas_w400_h100.jpg', $thumb);

        //Using the `anchor` option
        $thumb = $this->getThumbCreatorInstance()->resizeCanvas(300, 300, ['anchor' => 'bottom'])->save();
        $this->assertImageFileEquals('resize_canvas_w300_h300_anchor_bottom.jpg', $thumb);

        //Using `relative` and `bgcolor` options
        $thumb = $this->getThumbCreatorInstance()
            ->resizeCanvas(300, 300, ['relative' => true, 'bgcolor' => '#000000'])
            ->save();
        $this->assertImageFileEquals('resize_canvas_w700_h700_relative_and_black.jpg', $thumb);
    }

    /**
     * Test for several methods called in sequence on the same image (eg., `crop()` and `resize()`
     * @group imageEquals
     * @uses \Thumber\Cake\Utility\ThumbCreator::crop()
     * @uses \Thumber\Cake\Utility\ThumbCreator::resize()
     * @test
     */
    public function testSeveralMethods(): void
    {
        $thumb = $this->getThumbCreatorInstance('example_pic.jpg')->crop(600)->resize(200)->save();
        $this->assertImageFileEquals('crop_and_resize_w600_h200.jpg', $thumb);
    }
}
