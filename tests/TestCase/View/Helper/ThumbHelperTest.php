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

namespace Thumber\Test\TestCase\View\Helper;

use Cake\View\View;
use Thumber\TestSuite\TestCase;
use Thumber\View\Helper\ThumbHelper;

/**
 * ThumbHelperTest class
 */
class ThumbHelperTest extends TestCase
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

        unset($this->Thumb, $this->View);
    }

    /**
     * Test for `crop()` and `cropUrl()` methods
     * @return void
     * @test
     */
    public function testCrop()
    {
        $html = $this->Thumb->crop('400x400.png', ['width' => 200]);
        $expected = [
            'img' => [
                'src' => $this->Thumb->cropUrl('400x400.png', ['width' => 200]),
                'alt' => '',
            ],
        ];
        $this->assertHtml($expected, $html);
    }

    /**
     * Test for `resize()` and `resizeUrl()` methods
     * @return void
     * @test
     */
    public function testResize()
    {
        $html = $this->Thumb->resize('400x400.png', ['width' => 200]);
        $expected = [
            'img' => [
                'src' => $this->Thumb->resizeUrl('400x400.png', ['width' => 200]),
                'alt' => '',
            ],
        ];
        $this->assertHtml($expected, $html);
    }
}
