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
namespace Thumber\TestSuite;

use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\TestSuite\TestCase as CakeTestCase;
use Imagick;
use Reflection\ReflectionTrait;

/**
 * Thumber TestCase class
 */
abstract class TestCase extends CakeTestCase
{
    use ReflectionTrait;

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        foreach (glob(Configure::read(THUMBER . '.target') . DS . '*') as $file) {
            //@codingStandardsIgnoreLine
            unlink($file);
        }
    }

    /**
     * Internal method to create a copy of an image file
     * @param string $path Image file path
     * @return string
     */
    protected static function createCopy($path)
    {
        $result = tempnam(sys_get_temp_dir(), $path);

        $imagick = new Imagick($path);
        $imagick->stripImage();
        $imagick->writeImage($result);
        $imagick->clear();

        return $result;
    }

    /**
     * Asserts for the extension of a file
     * @param string $expected Expected extension
     * @param string $file File
     * @param string $message The failure message that will be appended to the
     *  generated message
     * @return void
     * @since 1.1.1
     */
    public static function assertFileExtension($expected, $file, $message = '')
    {
        self::assertEquals($expected, pathinfo($file, PATHINFO_EXTENSION), $message);
    }

    /**
     * Asserts that the contents of one image file is equal to the contents of
     *  another image file
     * @param string $expected Expected file
     * @param string $actual Actual file
     * @param string $message The failure message that will be appended to the
     *  generated message
     * @return void
     * @uses createCopy()
     */
    public static function assertImageFileEquals($expected, $actual, $message = '')
    {
        if (!Folder::isAbsolute($expected)) {
            $expected = Configure::read(THUMBER . '.comparingDir') . $expected;
        }

        self::assertFileExists($expected, $message);
        self::assertFileExists($actual, $message);

        $expectedCopy = self::createCopy($expected);
        $actualCopy = self::createCopy($actual);

        self::assertFileEquals($expectedCopy, $actualCopy, $message);

        //@codingStandardsIgnoreStart
        @unlink($expectedCopy);
        @unlink($actualCopy);
        //@codingStandardsIgnoreEnd
    }

    /**
     * Asserts that an image file has size
     * @param string $filename Path to the tested file
     * @param int $width Image width
     * @param int $height Image height
     * @param string $message The failure message that will be appended to the
     *  generated message
     * @return void
     */
    public static function assertImageSize($filename, $width, $height, $message = '')
    {
        self::assertFileExists($filename, $message);
        self::assertEquals(array_values(getimagesize($filename))[0], $width);
        self::assertEquals(array_values(getimagesize($filename))[1], $height);
    }

    /**
     * Asserts that a file has a MIME content type
     * @param string $filename Path to the tested file
     * @param string $mime MIME content type, like `text/plain` or `application/octet-stream`
     * @param string $message The failure message that will be appended to the
     *  generated message
     * @return void
     */
    public function assertMime($filename, $mime, $message = '')
    {
        parent::skipIf(!version_compare(PHP_VERSION, '7.0', '>') &&
            in_array($mime, ['image/x-ms-bmp', 'image/vnd.adobe.photoshop']));

        self::assertFileExists($filename, $message);
        self::assertEquals($mime, mime_content_type($filename), $message);
    }

    /**
     * Asserts for a valid thumbnail path
     * @param string $path Thumbnail path
     * @param string $message The failure message that will be appended to the
     *  generated message
     * @return void
     * @since 1.1.1
     */
    public static function assertThumbPath($path, $message = '')
    {
        $regex = sprintf(
            '/^%s[a-z0-9]{32}\.(%s)/',
            preg_quote(Configure::read(THUMBER . '.target') . DS, '/'),
            implode('|', ['bmp', 'gif', 'jpg', 'ico', 'png', 'psd', 'tiff'])
        );
        self::assertRegExp($regex, $path, $message);
    }
}
