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
namespace Thumber\Test\TestCase;

use Cake\TestSuite\TestCase;

/**
 * GlobalFunctionsTest class
 */
class GlobalFunctionsTest extends TestCase
{
    /**
     * Test for `isUrl()` global function
     * @return void
     * @test
     */
    public function testIsUrl()
    {
        //Http(s)
        $this->assertTrue(isUrl('https://www.example.com'));
        $this->assertTrue(isUrl('http://www.example.com'));
        $this->assertTrue(isUrl('www.example.com'));
        $this->assertTrue(isUrl('http://example.com'));
        $this->assertTrue(isUrl('http://example.com/file'));
        $this->assertTrue(isUrl('http://example.com/file.html'));
        $this->assertTrue(isUrl('www.example.com/file.html'));
        $this->assertTrue(isUrl('http://example.com/subdir/file'));

        //Ftp
        $this->assertTrue(isUrl('ftp://www.example.com'));
        $this->assertTrue(isUrl('ftp://example.com'));
        $this->assertTrue(isUrl('ftp://example.com/file.html'));

        //Missing "http" and/or "www"
        $this->assertFalse(isUrl('example.com'));

        //Files and dirs
        $this->assertFalse(isUrl('folder'));
        $this->assertFalse(isUrl(DS . 'folder'));
        $this->assertFalse(isUrl(DS . 'folder' . DS));
        $this->assertFalse(isUrl(DS . 'folder' . DS . 'file.txt'));
    }

    /**
     * Test for `rtr()` global function
     * @return void
     * @test
     */
    public function testRtr()
    {
        $result = rtr(ROOT . 'my' . DS . 'folder');
        $expected = 'my' . DS . 'folder';
        $this->assertEquals($expected, $result);

        $result = rtr('my' . DS . 'folder');
        $expected = 'my' . DS . 'folder';

        $this->assertEquals($expected, $result);
        $result = rtr(DS . 'my' . DS . 'folder');
        $expected = DS . 'my' . DS . 'folder';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test for `isUrl()` global function
     * @return void
     * @test
     */
    public function testThumbUrl()
    {
        if (!function_exists('getUrlFromPath')) {
            function getUrlFromPath($path)
            {
                return sprintf('http://localhost/thumb/%s', base64_encode(basename($path)));
            }
        }

        $path = 'mypath';
        $this->assertEquals(getUrlFromPath($path), thumbUrl($path));

        $path = 'mypath.gif';
        $this->assertEquals(getUrlFromPath($path), thumbUrl($path));

        $path = 'dir/mypath';
        $this->assertEquals(getUrlFromPath($path), thumbUrl($path));

        $path = 'dir/mypath.gif';
        $this->assertEquals(getUrlFromPath($path), thumbUrl($path));

        $path = '/dir/mypath';
        $this->assertEquals(getUrlFromPath($path), thumbUrl($path));

        $path = '/dir/mypath.gif';
        $this->assertEquals(getUrlFromPath($path), thumbUrl($path));
    }
}
