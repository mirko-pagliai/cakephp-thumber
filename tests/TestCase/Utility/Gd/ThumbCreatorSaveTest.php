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

namespace Thumber\Test\TestCase\Utility\Gd;

use Cake\Core\Configure;
use Thumber\Test\TestCase\Utility\ThumbCreatorSaveTest as BaseThumbCreatorSaveTest;

/**
 * ThumbCreatorSaveTest class.
 *
 * These tests use the GD library.
 */
class ThumbCreatorSaveTest extends BaseThumbCreatorSaveTest
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

        Configure::write('Thumbs.driver', 'gd');
        Configure::write('Thumbs.comparingDir', TESTS . DS . 'comparing_files' . DS . 'gd' . DS);
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        Configure::write('Thumbs.driver', 'imagick');
        Configure::write('Thumbs.comparingDir', TESTS . DS . 'comparing_files' . DS . 'imagick' . DS);
    }
}
