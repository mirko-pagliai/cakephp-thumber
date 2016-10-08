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

namespace Thumber\Test\TestCase\TestSuite;

use Thumber\TestSuite\TestCase;

/**
 * TestCaseTest class
 */
class TestCaseTest extends TestCase
{
    /**
     * Test for `assertImageFileEquals()` method
     * @ŧest
     */
    public function testAssertImageFileEquals()
    {
        $original = COMPARING_DIR . 'resize_w200_h200.jpg';
        $copy = tempnam(sys_get_temp_dir(), $original);

        copy($original, $copy);

        $this->assertImageFileEquals($original, $copy);
    }

    /**
     * Test for `assertImageSize()` method
     * @ŧest
     */
    public function testAssertImageSize()
    {
        $file = COMPARING_DIR . 'resize_w200_h300_noAspectRatio.jpg';

        $this->assertImageSize($file, 200, 300);
        $this->assertImageSize(
            $file,
            array_values(getimagesize($file))[0],
            array_values(getimagesize($file))[1]
        );
    }

    /**
     * Test for `assertMime()` method
     * @ŧest
     */
    public function testAssertMime()
    {
        $file = tempnam(sys_get_temp_dir(), 'test_file.txt');

        file_put_contents($file, 'this is a test file');

        $this->assertMime($file, mime_content_type($file));
        $this->assertMime($file, 'text/plain');

        unlink($file);
    }
}
