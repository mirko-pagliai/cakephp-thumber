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
