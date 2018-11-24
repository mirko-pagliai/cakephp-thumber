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
namespace Thumber\Test\TestCase\Routing\Middleware;

use Cake\Core\Configure;
use Cake\View\View;
use Thumber\TestSuite\IntegrationTestCase;
use Thumber\ThumbsPathTrait;
use Thumber\Utility\ThumbCreator;
use Thumber\View\Helper\ThumbHelper;

/**
 * ThumbnailMiddlewareTest class
 */
class ThumbnailMiddlewareTest extends IntegrationTestCase
{
    use ThumbsPathTrait;

    /**
     * Called before every test method
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->disableErrorHandlerMiddleware();

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
        if (Configure::readOrFail(THUMBER . '.driver') == 'imagick') {
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
            $lastModified = $this->_response->getHeader('Last-Modified')[0];
            $this->assertNotEmpty($lastModified);
        }

        //It still requires the last thumbnail file. It gets the 304 status code
        sleep(1);
        $this->configRequest(['headers' => ['If-Modified-Since' => $lastModified]]);
        $this->get($url);
        $this->assertResponseCode(304);

        //Deletes the last thumbnail file. Now the `Last-Modified` header is different
        safe_unlink($thumb);
        sleep(1);
        $thumb = (new ThumbCreator($file))->resize(200)->save();
        $this->get($url);
        $this->assertResponseOk();
        $this->assertNotEquals($lastModified, $this->_response->getHeader('Last-Modified')[0]);
    }

    /**
     * Test for `asset()` method, with a a no existing file
     * @expectedException Thumber\Http\Exception\ThumbNotFoundException
     * @expectedExceptionMessageRegExp /^File `[\w\/:\\]+` doesn't exist$/
     * @test
     */
    public function testThumbNoExistingFile()
    {
        $this->get('/thumb/' . base64_encode('noExistingFile'));
    }
}
