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
use Thumber\ThumbTrait;
use Thumber\Utility\ThumbCreator;

/**
 * ThumbCreatorSaveTest class
 */
class ThumbCreatorSaveTest extends TestCase
{
    use ThumbTrait;

    /**
     * Test for `save()` method
     * @test
     */
    public function testSave()
    {
        $extensions = [
            'gif' => 'image/gif',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
        ];

        //Adds some extensions only for the `imagick` driver
        if ($this->getDriver() == 'imagick') {
            $extensions += [
                'bmp' => 'image/x-ms-bmp',
                'ico' => 'image/x-icon',
                'psd' => 'image/vnd.adobe.photoshop',
                'tif' => 'image/tiff',
                'tiff' => 'image/tiff',
            ];
        }

        foreach ($extensions as $extension => $expectedMimetype) {
            $thumb = (new ThumbCreator('400x400.' . $extension))->resize(200)->save();
            $this->assertThumbPath($thumb);
            $this->assertMime($thumb, $expectedMimetype);

            //Using `format` option
            $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['format' => $extension]);
            $this->assertThumbPath($thumb);
            $this->assertMime($thumb, $expectedMimetype);

            //Using `target` option
            $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['target' => 'image.' . $extension]);
            $this->assertEquals($this->getPath('image.' . $extension), $thumb);
            $this->assertMime($thumb, $expectedMimetype);
        }
    }

    /**
     * Test for `save()` method, using an invalid file as input.
     *
     * This test runs only for the `gd` driver.
     * @expectedException RuntimeException
     * @expectedExceptionMessage Image type `text/x-php` is not supported by this driver
     * @ŧest
     */
    public function testSaveFromInvalidFileGd()
    {
        $this->skipIf($this->getDriver() != 'gd');

        (new ThumbCreator(APP . 'config' . DS . 'routes.php'))->resize(200)->save();
    }

    /**
     * Test for `save()` method, using an invalid file as input.
     *
     * This test runs only for the `imagick` driver.
     * @expectedException RuntimeException
     * @expectedExceptionMessage Unable to read image from file `tests/test_app/config/routes.php`
     * @ŧest
     */
    public function testSaveFromInvalidFileImagick()
    {
        $this->skipIf($this->getDriver() != 'imagick');

        (new ThumbCreator(APP . 'config' . DS . 'routes.php'))->resize(200)->save();
    }

    /**
     * Test for `save()` method, using the same file with different arguments.
     *
     * So the two thumbnails will have the same prefix in the name, but a
     *  different suffix
     * @test
     */
    public function testSaveSameFileDifferentArguments()
    {
        $firstThumb = explode('_', basename((new ThumbCreator('400x400.png'))->resize(200)->save()));
        $secondThumb = explode('_', basename((new ThumbCreator('400x400.png'))->resize(300)->save()));

        $this->assertEquals($firstThumb[0], $secondThumb[0]);
        $this->assertNotEquals($firstThumb[1], $secondThumb[1]);
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
        //@codingStandardsIgnoreLine
        unlink($thumb);
        sleep(1);

        //Tries to create again the same thumbnail. Now the creation time is different
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save();
        $this->assertNotEquals($time, filemtime($thumb));
    }

    /**
     * Test for `save()` method, using the `quality` option
     * @ŧest
     */
    public function testSaveWithQuality()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200)->save(['quality' => 10]);
        $this->assertThumbPath($thumb);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `save()` method, using the `quality` option with an invalid value
     * @expectedException Intervention\Image\Exception\InvalidArgumentException
     * @ŧest
     */
    public function testSaveWithQualityInvalidValue()
    {
        (new ThumbCreator('400x400.jpg'))->resize(200)->save(['quality' => 101]);
    }

    /**
     * Test for `save()` method, using the `quality` option, equating images
     * @group imageEquals
     * @ŧest
     */
    public function testSaveWithQualityImageEquals()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200)->save(['quality' => 10]);
        $this->assertImageFileEquals('resize_w200_h200_quality_10.jpg', $thumb);
    }

    /**
     * Test for `save()` method, using the `target` option
     * @ŧest
     */
    public function testSaveWithTarget()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200)->save(['target' => 'thumb.jpg']);
        $this->assertEquals($this->getPath('thumb.jpg'), $thumb);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `save()` method, using the `format` option with an invalid file
     *  format
     * @expectedException Intervention\Image\Exception\NotSupportedException
     * @ŧest
     */
    public function testSaveWithInvalidFormat()
    {
        (new ThumbCreator('400x400.png'))->resize(200)->save(['format' => 'txt']);
    }

    /**
     * Test for `save()` method, using similar format names, as `jpeg` or `tif`
     * @test
     */
    public function testSaveWithSimilarFormat()
    {
        $file = (new ThumbCreator('400x400.png'))->resize(200)->save(['format' => 'jpeg']);
        $this->assertFileExtension('jpg', $file);

        $this->skipIf($this->getDriver() === 'gd');

        $file = (new ThumbCreator('400x400.png'))->resize(200)->save(['format' => 'tif']);
        $this->assertFileExtension('tiff', $file, PATHINFO_EXTENSION);
    }

    /**
     * Test for `save()` method, using the `target` option with an invalid file
     *  format
     * @expectedException Intervention\Image\Exception\NotSupportedException
     * @test
     */
    public function testSaveInvalidTargetFormat()
    {
        (new ThumbCreator('400x400.png'))->resize(200)->save(['target' => 'image.txt']);
    }

    /**
     * Test for `save()` method, using the `target` option with a no existing
     *  directory target
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage The directory `/tmp/noExistingDir` is not writeable
     * @test
     */
    public function testSaveInvalidTargetDir()
    {
        (new ThumbCreator('400x400.png'))->resize(200)
            ->save(['target' => TMP . 'noExistingDir' . DS . 'thumb.jpg']);
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
