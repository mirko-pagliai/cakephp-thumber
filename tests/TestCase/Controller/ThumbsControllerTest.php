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
namespace Thumber\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestCase;
use Cake\View\View;
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

        $this->View = new View();
        $this->Thumb = new ThumbHelper($this->View);
    }

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
     * Test for `thumb()` method, with a a no existing file
     * @return void
     * @test
     */
    public function testThumbNoExistingFile()
    {
        $this->get('/thumb/noExistingFile');
        $this->assertResponseError();
    }

    /**
     * Test for `thumb()` method, with a gif file
     * @return void
     * @test
     */
    public function testThumbWithGif()
    {
        $file = '400x400.gif';

        //Thumbnail path
        $thumb = (new ThumbCreator($file))->resize(200)->save();

        //Url from helper
        $url = $this->Thumb->resizeUrl($file, ['width' => 200]);

        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('image/gif');
        $this->assertFileResponse($thumb);
    }

    /**
     * Test for `thumb()` method, with a jpeg file
     * @return void
     * @test
     */
    public function testThumbWithJpeg()
    {
        $file = '400x400.jpeg';

        //Thumbnail path
        $thumb = (new ThumbCreator($file))->resize(200)->save();

        //Url from helper
        $url = $this->Thumb->resizeUrl($file, ['width' => 200]);

        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('image/jpeg');
        $this->assertFileResponse($thumb);
    }

    /**
     * Test for `thumb()` method, with a jpg file
     * @return void
     * @test
     */
    public function testThumbWithJpg()
    {
        $file = '400x400.jpg';

        //Thumbnail path
        $thumb = (new ThumbCreator($file))->resize(200)->save();

        //Url from helper
        $url = $this->Thumb->resizeUrl($file, ['width' => 200]);

        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('image/jpeg');
        $this->assertFileResponse($thumb);
    }

    /**
     * Test for `thumb()` method, with a png file
     * @return void
     * @test
     */
    public function testThumbWithPng()
    {
        $file = '400x400.png';

        //Thumbnail path
        $thumb = (new ThumbCreator($file))->resize(200)->save();

        //Url from helper
        $url = $this->Thumb->resizeUrl($file, ['width' => 200]);

        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('image/png');
        $this->assertFileResponse($thumb);
    }
}
