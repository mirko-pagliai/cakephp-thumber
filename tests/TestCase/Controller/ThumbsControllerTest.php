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

use Cake\View\View;
use Thumber\Controller\ThumbsController;
use Thumber\TestSuite\IntegrationTestCase;
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

        $this->Thumb = new ThumbHelper(new View);
    }

    /**
     * Test for `thumb()` method, with some files
     * @test
     */
    public function testThumb()
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

        foreach ($extensions as $extension => $expectedMimeType) {
            $file = '400x400.' . $extension;
            $thumb = (new ThumbCreator($file))->resize(200)->save();
            $url = $this->Thumb->resizeUrl($file, [
                'format' => pathinfo($file, PATHINFO_EXTENSION),
                'width' => 200,
            ], ['fullBase' => false]);

            $this->get($url);
            $this->assertResponseOk();
            $this->assertFileResponse($thumb);
            $this->assertContentType($expectedMimeType);

            //Gets the `Last-Modified` header
            $lastModified = $this->_response->header()['Last-Modified'];
            $this->assertNotEmpty($lastModified);
        }

        //It still requires the last thumbnail file. It gets the 304 status code
        sleep(1);
        $this->configRequest(['headers' => ['If-Modified-Since' => $lastModified]]);
        $this->get($url);
        $this->assertResponseCode(304);

        //Deletes the last thumbnail file. Now the `Last-Modified` header is different
        @unlink($thumb);
        sleep(1);
        $thumb = (new ThumbCreator($file))->resize(200)->save();
        $this->get($url);
        $this->assertResponseOk();
        $this->assertNotEquals($lastModified, $this->_response->header()['Last-Modified']);
    }

    /**
     * Test for `asset()` method, with a a no existing file
     * @expectedException Thumber\Http\Exception\ThumbNotFoundException
     * @expectedExceptionMessageRegExp /^File `[\w\/:\\]+` doesn't exist$/
     * @test
     */
    public function testThumbNoExistingFile()
    {
        (new ThumbsController)->thumb(base64_encode('noExistingFile'));
    }

    /**
     * Test for `thumb()` method, with a a no existing file
     * @test
     */
    public function testThumbNoExistingFileResponse()
    {
        $this->get(base64_encode('noExistingFile'));
        $this->assertResponseError();
    }
}
