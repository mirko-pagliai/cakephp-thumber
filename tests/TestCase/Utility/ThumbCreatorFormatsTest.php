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

namespace Thumber\Test\TestCase\Utility;

use Cake\Core\Configure;
use Thumber\TestSuite\TestCase;
use Thumber\Utility\ThumbCreator;

/**
 * ThumbCreatorFormatsTest class
 */
class ThumbCreatorFormatsTest extends TestCase
{
    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        //Deletes all thumbnails
        foreach (glob(Configure::read('Thumbs.target') . DS . '*') as $file) {
            unlink($file);
        }
    }

    /**
     * Test for `save()` method, using a bmp file
     * @test
     */
    public function testSaveBmp()
    {
        $thumb = (new ThumbCreator('400x400.bmp'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.bmp/', preg_quote(Configure::read('Thumbs.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/x-ms-bmp');
    }

    /**
     * Test for `save()` method, using a gif file
     * @test
     */
    public function testSaveGif()
    {
        $thumb = (new ThumbCreator('400x400.gif'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.gif/', preg_quote(Configure::read('Thumbs.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/gif');
    }

    /**
     * Test for `save()` method, using a ico file
     * @test
     */
    public function testSaveIco()
    {
        $thumb = (new ThumbCreator('400x400.ico'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.ico/', preg_quote(Configure::read('Thumbs.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/x-icon');
    }

    /**
     * Test for `save()` method, using jpeg file
     * @test
     */
    public function testSaveJpeg()
    {
        $thumb = (new ThumbCreator('400x400.jpeg'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.jpg$/', preg_quote(Configure::read('Thumbs.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `save()` method, using jpeg file
     * @test
     */
    public function testSaveJpg()
    {
        $thumb = (new ThumbCreator('400x400.jpg'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.jpg$/', preg_quote(Configure::read('Thumbs.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/jpeg');
    }

    /**
     * Test for `save()` method, using a png file
     * @test
     */
    public function testSavePng()
    {
        $thumb = (new ThumbCreator('400x400.png'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.png/', preg_quote(Configure::read('Thumbs.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/png');
    }

    /**
     * Test for `save()` method, using a psd file
     * @test
     */
    public function testSavePsd()
    {
        $thumb = (new ThumbCreator('400x400.psd'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.psd/', preg_quote(Configure::read('Thumbs.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/vnd.adobe.photoshop');
    }

    /**
     * Test for `save()` method, using a tif file
     * @test
     */
    public function testSaveTif()
    {
        $thumb = (new ThumbCreator('400x400.tif'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.tiff/', preg_quote(Configure::read('Thumbs.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/tiff');
    }

    /**
     * Test for `save()` method, using a tiff file
     * @test
     */
    public function testSaveTiff()
    {
        $thumb = (new ThumbCreator('400x400.tiff'))->resize(200)->save();
        $this->assertRegExp(
            sprintf('/^%s[a-z0-9]{32}\.tiff/', preg_quote(Configure::read('Thumbs.target') . DS, '/')),
            $thumb
        );
        $this->assertMime($thumb, 'image/tiff');
    }
}
