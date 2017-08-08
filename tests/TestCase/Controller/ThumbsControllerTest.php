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
namespace Thumber\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestCase;
use Cake\View\View;
use Thumber\Controller\ThumbsController;
use Thumber\Utility\ThumbCreator;
use Thumber\View\Helper\ThumbHelper;

/**
 * ThumbsControllerTest class
 */
class ThumbsControllerTest extends IntegrationTestCase
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

        $this->View = new View();
        $this->Thumb = new ThumbHelper($this->View);
    }

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
     * Test for `asset()` method, with a a no existing file
     * @expectedException Thumber\Network\Exception\ThumbNotFoundException
     * @expectedExceptionMessage File `/tmp/thumbs/noExistingFile` doesn't exist
     * @test
     */
    public function testThumbNoExistingFile()
    {
        (new ThumbsController)->thumb(base64_encode('noExistingFile'));
    }

    /**
     * Test for `thumb()` method, with a a no existing file
     * @return void
     * @test
     */
    public function testThumbNoExistingFileResponse()
    {
        $this->get(base64_encode('noExistingFile'));
        $this->assertEquals(404, $this->_response->getStatusCode());
        $this->assertNull($this->_response->getFile());
        $this->assertResponseError();
    }

    /**
     * Test for `thumb()` method, with a bmp file
     * @return void
     * @test
     */
    public function testThumbWithBmp()
    {
        $file = '400x400.bmp';
        $thumb = (new ThumbCreator($file))->resize(200)->save();
        $url = $this->Thumb->resizeUrl($file, ['format' => 'bmp', 'width' => 200], ['fullBase' => false]);

        $this->get($url);
        $this->assertResponseOk();

        if (version_compare(PHP_VERSION, '7.0', '>')) {
            $this->assertContentType('image/x-ms-bmp');
        }

        $this->assertFileResponse($thumb);
    }

    /**
     * Test for `thumb()` method, with a gif file
     * @return void
     * @test
     */
    public function testThumbWithGif()
    {
        $file = '400x400.gif';
        $thumb = (new ThumbCreator($file))->resize(200)->save();
        $url = $this->Thumb->resizeUrl($file, ['format' => 'gif', 'width' => 200], ['fullBase' => false]);

        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('image/gif');
        $this->assertFileResponse($thumb);
    }

    /**
     * Test for `thumb()` method, with a ico file
     * @return void
     * @test
     */
    public function testThumbWithIco()
    {
        $file = '400x400.ico';
        $thumb = (new ThumbCreator($file))->resize(200)->save();
        $url = $this->Thumb->resizeUrl($file, ['format' => 'ico', 'width' => 200], ['fullBase' => false]);

        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('image/x-icon');
        $this->assertFileResponse($thumb);
    }

    /**
     * Test for `thumb()` method, with a jpeg file
     * @return void
     * @test
     */
    public function testThumbWithJpeg()
    {
        $file = '400x400.jpeg';
        $thumb = (new ThumbCreator($file))->resize(200)->save();
        $url = $this->Thumb->resizeUrl($file, ['format' => 'jpg', 'width' => 200], ['fullBase' => false]);

        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('image/jpeg');
        $this->assertFileResponse($thumb);
    }

    /**
     * Test for `thumb()` method, with a jpg file
     * @return void
     * @test
     */
    public function testThumbWithJpg()
    {
        $file = '400x400.jpg';
        $thumb = (new ThumbCreator($file))->resize(200)->save();
        $url = $this->Thumb->resizeUrl($file, ['format' => 'jpg', 'width' => 200], ['fullBase' => false]);

        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('image/jpeg');
        $this->assertFileResponse($thumb);
    }

    /**
     * Test for `thumb()` method, with a png file
     * @return void
     * @test
     */
    public function testThumbWithPng()
    {
        $file = '400x400.png';
        $thumb = (new ThumbCreator($file))->resize(200)->save();
        $url = $this->Thumb->resizeUrl($file, ['format' => 'png', 'width' => 200], ['fullBase' => false]);

        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('image/png');
        $this->assertFileResponse($thumb);
    }

    /**
     * Test for `thumb()` method, with a psd file
     * @return void
     * @test
     */
    public function testThumbWithPsd()
    {
        $file = '400x400.psd';
        $thumb = (new ThumbCreator($file))->resize(200)->save();
        $url = $this->Thumb->resizeUrl($file, ['format' => 'psd', 'width' => 200], ['fullBase' => false]);

        $this->get($url);
        $this->assertResponseOk();

        if (version_compare(PHP_VERSION, '7.0', '>')) {
            $this->assertContentType('image/vnd.adobe.photoshop');
        }

        $this->assertFileResponse($thumb);
    }

    /**
     * Test for `thumb()` method, with a tif file
     * @return void
     * @test
     */
    public function testThumbWithTif()
    {
        $file = '400x400.tif';
        $thumb = (new ThumbCreator($file))->resize(200)->save();
        $url = $this->Thumb->resizeUrl($file, ['format' => 'tiff', 'width' => 200], ['fullBase' => false]);

        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('image/tiff');
        $this->assertFileResponse($thumb);
    }

    /**
     * Test for `thumb()` method, with a tiff file
     * @return void
     * @test
     */
    public function testThumbWithTiff()
    {
        $file = '400x400.tiff';
        $thumb = (new ThumbCreator($file))->resize(200)->save();
        $url = $this->Thumb->resizeUrl($file, ['format' => 'tiff', 'width' => 200], ['fullBase' => false]);

        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('image/tiff');
        $this->assertFileResponse($thumb);
    }
}
