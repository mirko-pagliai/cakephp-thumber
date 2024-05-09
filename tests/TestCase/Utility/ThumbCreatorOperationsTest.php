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
use Thumber\Cake\Utility\ThumbCreator;

/**
 * ThumbCreatorOperationsTest class
 * @uses \Thumber\Cake\Utility\ThumbCreator
 */
class ThumbCreatorOperationsTest extends TestCase
{
    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::crop()
     */
    public function testCrop(): void
    {
        $thumb = $this->getThumbCreatorInstance()->crop(200, 200)->save();
        $this->assertImageSize(200, 200, $thumb);

        //Only width
        $thumb = $this->getThumbCreatorInstance()->crop(200)->save();
        $this->assertImageSize(200, 200, $thumb);

        //In this case, the width will be the original size
        $thumb = $this->getThumbCreatorInstance()->crop(400, 200)->save();
        $this->assertImageSize(400, 200, $thumb);

        //Using `x` and `y` options
        $thumb = $this->getThumbCreatorInstance()->crop(200, 200, ['x' => 50, 'y' => 50])->save();
        $this->assertImageSize(200, 200, $thumb);
    }

    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::crop()
     */
    public function testCropInvalidXOption(): void
    {
        $this->expectExceptionMessage('The `x` option must be an integer');
        $this->getThumbCreatorInstance()->crop(200, 200, ['x' => 'string'])->save();
    }

    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::crop()
     */
    public function testCropInvalidYOption(): void
    {
        $this->expectExceptionMessage('The `y` option must be an integer');
        $this->getThumbCreatorInstance()->crop(200, 200, ['x' => 50, 'y' => 'string'])->save();
    }

    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::crop()
     */
    public function testCropWithoutParameters(): void
    {
        $this->expectExceptionMessage('You have to set at least the width for the `' . ThumbCreator::class . '::crop()` method');
        $this->getThumbCreatorInstance()->crop(0)->save();
    }

    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::fit()
     */
    public function testFit(): void
    {
        $thumb = $this->getThumbCreatorInstance()->fit(200, 400)->save();
        $this->assertImageSize(200, 400, $thumb);

        //Only width
        $thumb = $this->getThumbCreatorInstance()->fit(200)->save();
        $this->assertImageSize(200, 200, $thumb);

        //Using the `position` option
        $thumb = $this->getThumbCreatorInstance()->fit(200, 200, ['position' => 'top'])->save();
        $this->assertImageSize(200, 200, $thumb);

        //Using the `upsize` option
        //In this case, the thumbnail will keep the original dimensions
        $thumb = $this->getThumbCreatorInstance()->fit(450, 450, ['upsize' => true])->save();
        $this->assertImageSize(400, 400, $thumb);

        //Using the `upsize` option
        //In this case, the thumbnail will exceed the original size
        $thumb = $this->getThumbCreatorInstance()->fit(450, 450, ['upsize' => false])->save();
        $this->assertImageSize(450, 450, $thumb);

        //Without parameters
        $this->expectExceptionMessage('You have to set at least the width for the `' . ThumbCreator::class . '::fit()` method');
        $this->getThumbCreatorInstance()->fit()->save();
    }

    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::resize()
     */
    public function testResize(): void
    {
        $thumb = $this->getThumbCreatorInstance()->resize(200, 200)->save();
        $this->assertImageSize(200, 200, $thumb);

        //Only width
        $thumb = $this->getThumbCreatorInstance()->resize(200)->save();
        $this->assertImageSize(200, 200, $thumb);

        //Using  the `aspectRatio` option
        //In this case, the thumbnail will keep the ratio
        $thumb = $this->getThumbCreatorInstance()->resize(200, 300, ['aspectRatio' => true])->save();
        $this->assertImageSize(200, 200, $thumb);

        //Using  the `aspectRatio` option
        //In this case, the thumbnail will not maintain the ratio
        $thumb = $this->getThumbCreatorInstance()->resize(200, 300, ['aspectRatio' => false])->save();
        $this->assertImageSize(200, 300, $thumb);

        //Using the `upsize` option
        //In this case, the thumbnail will keep the original dimensions
        $thumb = $this->getThumbCreatorInstance()->resize(450, 450, ['upsize' => true])->save();
        $this->assertImageSize(400, 400, $thumb);

        //Using the `upsize` option
        //In this case, the thumbnail will exceed the original size
        $thumb = $this->getThumbCreatorInstance()->resize(450, 450, ['upsize' => false])->save();
        $this->assertImageSize(450, 450, $thumb);

        //Using `aspectRatio` and `upsize` options
        //In this case, the thumbnail will keep the ratio and the original dimensions
        $thumb = $this->getThumbCreatorInstance()->resize(500, 600, ['aspectRatio' => true, 'upsize' => true])->save();
        $this->assertImageSize(400, 400, $thumb);

        //Using `aspectRatio` and `upsize` options
        //In this case, the thumbnail will not keep the ratio and the original dimensions
        $thumb = $this->getThumbCreatorInstance()->resize(500, 600, ['aspectRatio' => false, 'upsize' => false])->save();
        $this->assertImageSize(500, 600, $thumb);

        //Without parameters
        $this->expectExceptionMessage('You have to set at least the width for the `' . ThumbCreator::class . '::resize()` method');
        $this->getThumbCreatorInstance()->resize(0)->save();
    }

    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::resizeCanvas()
     */
    public function testResizeCanvas(): void
    {
        $thumb = $this->getThumbCreatorInstance()->resizeCanvas(200, 100)->save();
        $this->assertImageSize(200, 100, $thumb);

        //Only width
        $thumb = $this->getThumbCreatorInstance()->resizeCanvas(200)->save();
        $this->assertImageSize(200, 200, $thumb);

        //Using the `anchor` option
        $thumb = $this->getThumbCreatorInstance()->resizeCanvas(300, 300, ['anchor' => 'bottom'])->save();
        $this->assertImageSize(300, 300, $thumb);

        //Using `relative` and `bgcolor` options
        $thumb = $this->getThumbCreatorInstance()
            ->resizeCanvas(300, 300, ['relative' => true, 'bgcolor' => '#000000'])
            ->save();
        $this->assertImageSize(700, 700, $thumb);

        //Without parameters
        $this->expectExceptionMessage('You have to set at least the width for the `' . ThumbCreator::class . '::resizeCanvas()` method');
        $this->getThumbCreatorInstance()->resizeCanvas(0)->save();
    }

    /**
     * Test for several methods called in sequence on the same image (eg., `crop()` and `resize()`
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::crop()
     * @uses \Thumber\Cake\Utility\ThumbCreator::resize()
     */
    public function testSeveralMethods(): void
    {
        $thumb = $this->getThumbCreatorInstance()->crop(600)->resize(200)->save();
        $this->assertImageSize(200, 200, $thumb);
    }
}
