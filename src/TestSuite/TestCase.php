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

use Cake\TestSuite\TestCase as CakeTestCase;

/**
 * Thumber TestCase class
 */
abstract class TestCase extends CakeTestCase
{
    /**
     * Internal method to create a copy of an image file
     * @param string $path Image file path
     * @return string
     */
    protected static function _createCopy($path)
    {
        $result = tempnam(sys_get_temp_dir(), $path);

        $imagick = new \Imagick($path);
        $imagick->stripImage();
        $imagick->writeImage($result);
        $imagick->clear();

        return $result;
    }

    /**
     * Asserts that the contents of one image file is equal to the contents of
     * another image file
     * @param string $expected Expected file
     * @param string $actual Actual file
     * @param string $message Error message
     * @return void
     * @uses _createCopy()
     */
    public static function assertImageFileEquals($expected, $actual, $message = '')
    {
        self::assertFileExists($expected, $message);
        self::assertFileExists($actual, $message);

        $expectedCopy = self::_createCopy($expected);
        $actualCopy = self::_createCopy($actual);

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
     * @param string $message Error message
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
     * @param string $mime  MIME content type, like `text/plain` or `application/octet-stream`
     * @param string $message Error message
     * @return void
     */
    public static function assertMime($filename, $mime, $message = '')
    {
        self::assertFileExists($filename, $message);
        self::assertEquals($mime, mime_content_type($filename), $message);
    }
}
