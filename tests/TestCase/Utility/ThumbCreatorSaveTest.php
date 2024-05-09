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

use Cake\Core\Configure;
use Intervention\Image\Exception\InvalidArgumentException;
use Intervention\Image\Exception\NotSupportedException;
use Thumber\Cake\TestSuite\TestCase;
use Tools\Filesystem;

/**
 * ThumbCreatorSaveTest class
 * @uses \Thumber\Cake\Utility\ThumbCreator
 */
class ThumbCreatorSaveTest extends TestCase
{
    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::save()
     */
    public function testSave(): void
    {
        $extensions = [
            'gif' => 'image/gif',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
        ];

        //Adds some extensions only for the `imagick` driver
        $extensions += Configure::readOrFail('Thumber.driver') == 'imagick' ? [
            'psd' => 'image/vnd.adobe.photoshop',
            'tif' => 'image/tiff',
            'tiff' => 'image/tiff',
        ] : [];

        foreach ($extensions as $extension => $expectedMimetype) {
            $thumb = $this->getThumbCreatorInstance('400x400.' . $extension)->resize(200)->save();
            $this->assertThumbPath($thumb);
            $this->assertSame($expectedMimetype, mime_content_type($thumb));

            //Using `format` option
            $thumb = $this->getThumbCreatorInstance()->resize(200)->save(['format' => $extension]);
            $this->assertThumbPath($thumb);
            $this->assertSame($expectedMimetype, mime_content_type($thumb));

            //Using `target` option
            $thumb = $this->getThumbCreatorInstance()->resize(200)->save(['target' => 'image.' . $extension]);
            $this->assertEquals(Filesystem::instance()->concatenate(THUMBER_TARGET, 'image.' . $extension), $thumb);
            $this->assertSame($expectedMimetype, mime_content_type($thumb));
        }
    }

    /**
     * Test for `save()` method, if unable to create file
     * @requires OS Linux
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::save()
     */
    public function testSaveUnableToCreateFile(): void
    {
        $this->expectExceptionMessage('Unable to create file `' . DS . 'noExisting`');
        $this->getThumbCreatorInstance('400x400.jpg')->resize(200)->save(['target' => DS . 'noExisting']);
    }

    /**
     * Test for `save()` method, using the same file with different arguments, so the two thumbnails will have the same
     *  prefix in the name, but a different suffix
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::save()
     */
    public function testSaveSameFileDifferentArguments(): void
    {
        $firstThumb = explode('_', basename($this->getThumbCreatorInstance()->resize(200)->save()));
        $secondThumb = explode('_', basename($this->getThumbCreatorInstance()->resize(300)->save()));
        $this->assertSame($firstThumb[0], $secondThumb[0]);
        $this->assertNotSame($firstThumb[1], $secondThumb[1]);
    }

    /**
     * Test for `save()` method. It tests the thumbnails is created only if it does not exist
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::save()
     */
    public function testSaveReturnsExistingThumb(): void
    {
        //Creates the thumbnail and gets the creation time
        $time = filemtime($this->getThumbCreatorInstance()->resize(200)->save());

        //Tries to create again the same thumbnail. Now the creation time is the same
        $thumb = $this->getThumbCreatorInstance()->resize(200)->save();
        $this->assertEquals($time, filemtime($thumb));

        //Deletes and waits 1 second, then tries to create again the same thumbnail. Now the creation time is different
        if (is_writable($thumb)) {
            unlink($thumb);
        }
        sleep(1);
        $newTime = filemtime($this->getThumbCreatorInstance()->resize(200)->save());
        $this->assertNotEquals($time, $newTime);
    }

    /**
     * Test for `save()` method, using the `quality` option
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::save()
     */
    public function testSaveWithQuality(): void
    {
        $this->assertThumbPath($this->getThumbCreatorInstance()->resize(200)->save(['quality' => 10]));

        //With an invalid value
        $this->expectException(InvalidArgumentException::class);
        $this->getThumbCreatorInstanceWithSave('', ['quality' => 101]);
    }

    /**
     * Test for `save()` method, using the `quality` option, equating images
     * @group imageEquals
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::save()
     */
    public function testSaveWithQualityImageEquals(): void
    {
        $thumb = $this->getThumbCreatorInstance()->resize(200)->save(['quality' => 10]);
        $this->assertImageFileEquals('resize_w200_h200_quality_10.jpg', $thumb);
    }

    /**
     * Test for `save()` method, using the `target` option
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::save()
     */
    public function testSaveWithTarget(): void
    {
        $thumb = $this->getThumbCreatorInstance()->resize(200)->save(['target' => 'thumb.png']);
        $this->assertEquals(Filesystem::instance()->concatenate(THUMBER_TARGET, 'thumb.png'), $thumb);
        $this->assertSame('image/png', mime_content_type($thumb));

        //With an invalid file format
        $this->expectException(NotSupportedException::class);
        $this->getThumbCreatorInstanceWithSave('', ['format' => 'txt']);
    }

    /**
     * Test for `save()` method, using similar format names, as `jpeg` or `tif`
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::save()
     */
    public function testSaveWithSimilarFormat(): void
    {
        $Filesystem = new Filesystem();

        $file = $this->getThumbCreatorInstance()->resize(200)->save(['format' => 'jpeg']);
        $this->assertSame('jpg', $Filesystem->getExtension($file));

        $this->skipIfDriverIs('gd');
        $file = $this->getThumbCreatorInstance()->resize(200)->save(['format' => 'tif']);
        $this->assertSame('tiff', $Filesystem->getExtension($file));

        //Using the `target` option with an invalid file
        $this->expectException(NotSupportedException::class);
        $this->getThumbCreatorInstanceWithSave('', ['target' => 'image.txt']);
    }

    /**
     * Test for `save()` method, without a valid method called before
     * @test
     * @uses \Thumber\Cake\Utility\ThumbCreator::save()
     */
    public function testSaveWithoutCallbacks(): void
    {
        $this->expectExceptionMessage('No valid method called before the `save()` method');
        $this->getThumbCreatorInstance()->save();
    }
}
