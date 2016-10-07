<?php
/**
 * This file is part of cakephp-thumber.
 *
 * cakephp-thumber is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * cakephp-thumber is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with cakephp-thumber.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */
namespace Thumber\TestSuite;

use Cake\TestSuite\TestCase as CakeTestCase;

/**
 * Thumber TestCase class
 */
abstract class TestCase extends CakeTestCase
{
    /**
     * Internal method to create a copy of a jpeg file
     * @param string $path Jpeg file path
     * @return string
     */
    protected static function _createJpegCopy($path)
    {
        $image = imagecreatefromjpeg($path);
        $result = tempnam(sys_get_temp_dir(), $path);
        imagejpeg($image, $result, 100);
        imagedestroy($image);

        return $result;
    }

    /**
     * Asserts that the contents of one jpeg file is equal to the contents of
     * another jpeg file
     * @param string $expected Expected jpeg file
     * @param string $actual Actual jpeg file
     * @param string $message Error message
     * @return void
     */
    public static function assertJpegFileEquals($expected, $actual, $message = '')
    {
        self::assertFileExists($expected, $message);
        self::assertFileExists($actual, $message);

        $expectedCopy = self::_createJpegCopy($expected);
        $actualCopy = self::_createJpegCopy($actual);

        self::assertFileEquals($expectedCopy, $actualCopy, $message);

        //@codingStandardsIgnoreStart
        @unlink($expectedCopy);
        @unlink($actualCopy);
        //@codingStandardsIgnoreEnd
    }
}
