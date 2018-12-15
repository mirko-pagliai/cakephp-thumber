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

use Cake\Core\Configure;
use Thumber\TestSuite\TestCase;

/**
 * ThumbCreatorSaveTest class
 */
class ThumbCreatorSaveTest extends TestCase
{
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
        $extensions += Configure::readOrFail('Thumber.driver') == 'imagick' ? [
            'bmp' => 'image/x-ms-bmp',
            'ico' => 'image/x-icon',
            'psd' => 'image/vnd.adobe.photoshop',
            'tif' => 'image/tiff',
            'tiff' => 'image/tiff',
        ] : [];

        foreach ($extensions as $extension => $expectedMimetype) {
            $thumb = $this->getThumbCreatorInstance('400x400.' . $extension)->resize(200)->save();
            $this->assertThumbPath($thumb);
            $this->assertFileMime($thumb, $expectedMimetype);

            //Using `format` option
            $thumb = $this->getThumbCreatorInstance()->resize(200)->save(['format' => $extension]);
            $this->assertThumbPath($thumb);
            $this->assertFileMime($thumb, $expectedMimetype);

            //Using `target` option
            $thumb = $this->getThumbCreatorInstance()->resize(200)->save(['target' => 'image.' . $extension]);
            $this->assertEquals($this->getPath('image.' . $extension), $thumb);
            $this->assertFileMime($thumb, $expectedMimetype);
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
        $this->skipIfDriverIs('imagick');

        $this->getThumbCreatorInstanceWithSave(APP . 'config' . DS . 'routes.php');
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
        $this->skipIfDriverIs('gd');

        $this->getThumbCreatorInstanceWithSave(APP . 'config' . DS . 'routes.php');
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
        $firstThumb = explode('_', basename($this->getThumbCreatorInstance()->resize(200)->save()));
        $secondThumb = explode('_', basename($this->getThumbCreatorInstance()->resize(300)->save()));

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
        $time = filemtime($this->getThumbCreatorInstance()->resize(200)->save());

        //Tries to create again the same thumbnail. Now the creation time is the same
        $thumb = $this->getThumbCreatorInstance()->resize(200)->save();
        $this->assertEquals($time, filemtime($thumb));

        //Deletes the thumbnail and wait 1 second
        safe_unlink($thumb);
        sleep(1);

        //Tries to create again the same thumbnail. Now the creation time is different
        $newTime = filemtime($this->getThumbCreatorInstance()->resize(200)->save());
        $this->assertNotEquals($time, $newTime);
    }

    /**
     * Test for `save()` method, using the `quality` option
     * @ŧest
     */
    public function testSaveWithQuality()
    {
        $thumb = $this->getThumbCreatorInstance()->resize(200)->save(['quality' => 10]);
        $this->assertThumbPath($thumb);
    }

    /**
     * Test for `save()` method, using the `quality` option with an invalid value
     * @expectedException Intervention\Image\Exception\InvalidArgumentException
     * @ŧest
     */
    public function testSaveWithQualityInvalidValue()
    {
        $this->getThumbCreatorInstanceWithSave(['quality' => 101]);
    }

    /**
     * Test for `save()` method, using the `quality` option, equating images
     * @group imageEquals
     * @ŧest
     */
    public function testSaveWithQualityImageEquals()
    {
        $thumb = $this->getThumbCreatorInstance()->resize(200)->save(['quality' => 10]);
        $this->assertImageFileEquals('resize_w200_h200_quality_10.jpg', $thumb);
    }

    /**
     * Test for `save()` method, using the `target` option
     * @ŧest
     */
    public function testSaveWithTarget()
    {
        $thumb = $this->getThumbCreatorInstance()->resize(200)->save(['target' => 'thumb.png']);
        $this->assertEquals($this->getPath('thumb.png'), $thumb);
        $this->assertFileMime($thumb, 'image/png');
    }

    /**
     * Test for `save()` method, using the `format` option with an invalid file
     *  format
     * @expectedException Intervention\Image\Exception\NotSupportedException
     * @ŧest
     */
    public function testSaveWithInvalidFormat()
    {
        $this->getThumbCreatorInstanceWithSave(['format' => 'txt']);
    }

    /**
     * Test for `save()` method, using similar format names, as `jpeg` or `tif`
     * @test
     */
    public function testSaveWithSimilarFormat()
    {
        $file = $this->getThumbCreatorInstance()->resize(200)->save(['format' => 'jpeg']);
        $this->assertFileExtension('jpg', $file);

        $this->skipIfDriverIs('gd');

        $file = $this->getThumbCreatorInstance()->resize(200)->save(['format' => 'tif']);
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
        $this->getThumbCreatorInstanceWithSave(['target' => 'image.txt']);
    }

    /**
     * Test for `save()` method, using the `target` option with a no existing
     *  directory target
     * @expectedException ErrorException
     * @expectedExceptionMessageRegExp /^File or directory `[\w\/:\\]+` is not writable$/
     * @test
     */
    public function testSaveInvalidTargetDir()
    {
        $this->getThumbCreatorInstanceWithSave(['target' => TMP . 'noExistingDir' . DS . 'thumb.jpg']);
    }

    /**
     * Test for `save()` method, without a valid method called before
     * @expectedException RuntimeException
     * @expectedExceptionMessage No valid method called before the `save` method
     */
    public function testSaveWithoutCallbacks()
    {
        $this->getThumbCreatorInstance()->save();
    }
}
