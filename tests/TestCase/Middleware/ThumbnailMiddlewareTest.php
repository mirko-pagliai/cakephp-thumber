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
namespace Thumber\Cake\Test\TestCase\Middleware;

use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\View\View;
use Thumber\Cake\Http\Exception\ThumbNotFoundException;
use Thumber\Cake\TestSuite\TestCase;
use Thumber\Cake\Utility\ThumbCreator;
use Thumber\Cake\View\Helper\ThumbHelper;
use Tools\Filesystem;

/**
 * ThumbnailMiddlewareTest class
 * @property \Cake\Http\Response $_response The response for the most recent request
 */
class ThumbnailMiddlewareTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * @test
     * @uses \Thumber\Cake\Middleware\ThumbnailMiddleware::process()
     */
    public function testThumb(): void
    {
        $this->loadPlugins(['Thumber/Cake' => []]);

        $extensions = [
            'gif' => 'image/gif',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
        ];

        //Adds some extensions only for the `imagick` driver
        if (Configure::readOrFail('Thumber.driver') == 'imagick') {
            $extensions += [
                'bmp' => 'image/x-ms-bmp',
                'ico' => 'image/vnd.microsoft.icon',
                'psd' => 'image/vnd.adobe.photoshop',
                'tif' => 'image/tiff',
                'tiff' => 'image/tiff',
            ];
        }

        $ThumbHelper = new ThumbHelper(new View());

        foreach ($extensions as $extension => $expectedMimeType) {
            $file = '400x400.' . $extension;
            $thumb = (new ThumbCreator($file))->resize(200)->save();
            $url = $ThumbHelper->resizeUrl($file, [
                'format' => pathinfo($file, PATHINFO_EXTENSION),
                'width' => 200,
            ], ['fullBase' => false]);

            $this->get($url);
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
        @unlink($thumb);
        sleep(1);
        (new ThumbCreator($file))->resize(200)->save();
        $this->get($url);
        $this->assertResponseOk();
        $this->assertNotEquals($lastModified, $this->_response->getHeader('Last-Modified')[0]);

        //With a no existing file
        $this->expectException(ThumbNotFoundException::class);
        $this->expectExceptionMessage('File `' . Filesystem::instance()->concatenate(THUMBER_TARGET, 'noExistingFile') . '` doesn\'t exist');
        $this->disableErrorHandlerMiddleware();
        $this->get('/thumb/' . base64_encode('noExistingFile'));
    }
}
