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
use Thumber\Utility\ThumbCreator;

/**
 * ThumbCreatorFormatsTest class
 */
class ThumbCreatorFormatsTest extends TestCase
{
    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        //Deletes all thumbnails
        foreach (glob(Configure::read(THUMBER . '.target') . DS . '*') as $file) {
            unlink($file);
        }
    }

    /**
     * Test for `save()` method, using a bmp file
     * @test
     */
    public function testSaveBmp()
    {
        $thumb = (new ThumbCreator('400x400.bmp'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.bmp/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );

        if (version_compare(PHP_VERSION, '7.0', '>')) {
            $this->assertMime($thumb, 'image/x-ms-bmp');
        }

        //Using `format` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['format' => 'bmp']);
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.bmp/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );

        if (version_compare(PHP_VERSION, '7.0', '>')) {
            $this->assertMime($thumb, 'image/x-ms-bmp');
        }

        //Using `target` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['target' => 'image.bmp']);
        $this->assertEquals(Configure::read(THUMBER . '.target') . DS . 'image.bmp', $thumb);

        if (version_compare(PHP_VERSION, '7.0', '>')) {
            $this->assertMime($thumb, 'image/x-ms-bmp');
        }
    }

    /**
     * Test for `save()` method, using a gif file
     * @test
     */
    public function testSaveGif()
    {
        $thumb = (new ThumbCreator('400x400.gif'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.gif/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/gif');

        //Using `format` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['format' => 'gif']);
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.gif/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/gif');

        //Using `target` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['target' => 'image.gif']);
        $this->assertEquals(Configure::read(THUMBER . '.target') . DS . 'image.gif', $thumb);
        $this->assertMime($thumb, 'image/gif');
    }

    /**
     * Test for `save()` method, using a ico file
     * @test
     */
    public function testSaveIco()
    {
        $thumb = (new ThumbCreator('400x400.ico'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.ico/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/x-icon');

        //Using `format` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['format' => 'ico']);
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.ico/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/x-icon');

        //Using `target` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['target' => 'image.ico']);
        $this->assertEquals(Configure::read(THUMBER . '.target') . DS . 'image.ico', $thumb);
        $this->assertMime($thumb, 'image/x-icon');
    }

    /**
     * Test for `save()` method, using jpeg file
     * @test
     */
    public function testSaveJpeg()
    {
        $thumb = (new ThumbCreator('400x400.jpeg'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.jpg$/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/jpeg');

        //Using `target` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['target' => 'image.jpeg']);
        $this->assertEquals(Configure::read(THUMBER . '.target') . DS . 'image.jpeg', $thumb);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `save()` method, using jpeg file
     * @test
     */
    public function testSaveJpg()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.jpg$/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/jpeg');

        //Using `format` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['format' => 'jpg']);
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.jpg/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/jpeg');

        //Using `target` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['target' => 'image.jpg']);
        $this->assertEquals(Configure::read(THUMBER . '.target') . DS . 'image.jpg', $thumb);
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `save()` method, using a png file
     * @test
     */
    public function testSavePng()
    {
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.png/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/png');

        //Using `format` option
        $thumb = (new ThumbCreator('400x400.gif'))->resize(200)->save(['format' => 'png']);
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.png/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/png');

        //Using `target` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['target' => 'image.png']);
        $this->assertEquals(Configure::read(THUMBER . '.target') . DS . 'image.png', $thumb);
        $this->assertMime($thumb, 'image/png');
    }

    /**
     * Test for `save()` method, using a psd file
     * @test
     */
    public function testSavePsd()
    {
        $thumb = (new ThumbCreator('400x400.psd'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.psd/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );

        if (version_compare(PHP_VERSION, '7.0', '>')) {
            $this->assertMime($thumb, 'image/vnd.adobe.photoshop');
        }

        //Using `format` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['format' => 'psd']);
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.psd/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );

        if (version_compare(PHP_VERSION, '7.0', '>')) {
            $this->assertMime($thumb, 'image/vnd.adobe.photoshop');
        }

        //Using `target` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['target' => 'image.psd']);
        $this->assertEquals(Configure::read(THUMBER . '.target') . DS . 'image.psd', $thumb);

        if (version_compare(PHP_VERSION, '7.0', '>')) {
            $this->assertMime($thumb, 'image/vnd.adobe.photoshop');
        }
    }

    /**
     * Test for `save()` method, using a tif file
     * @test
     */
    public function testSaveTif()
    {
        $thumb = (new ThumbCreator('400x400.tif'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.tiff/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/tiff');

        //Using `target` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['target' => 'image.tif']);
        $this->assertEquals(Configure::read(THUMBER . '.target') . DS . 'image.tif', $thumb);
        $this->assertMime($thumb, 'image/tiff');
    }

    /**
     * Test for `save()` method, using a tiff file
     * @test
     */
    public function testSaveTiff()
    {
        $thumb = (new ThumbCreator('400x400.tiff'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.tiff/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/tiff');

        //Using `format` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['format' => 'tiff']);
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.tiff/', preg_quote(Configure::read(THUMBER . '.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/tiff');

        //Using `target` option
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save(['target' => 'image.tiff']);
        $this->assertEquals(Configure::read(THUMBER . '.target') . DS . 'image.tiff', $thumb);
        $this->assertMime($thumb, 'image/tiff');
    }
}
