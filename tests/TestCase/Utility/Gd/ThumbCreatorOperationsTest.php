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
namespace Thumber\Test\TestCase\Utility\Gd;

use Cake\Core\Configure;
use Thumber\Test\TestCase\Utility\ThumbCreatorOperationsTest as BaseThumbCreatorOperationsTest;

/**
 * ThumbCreatorOperationsTest class.
 *
 * These tests use the GD library.
 */
class ThumbCreatorOperationsTest extends BaseThumbCreatorOperationsTest
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

        Configure::write(THUMBER . '.driver', 'gd');
        Configure::write(THUMBER . '.comparingDir', TESTS . DS . 'comparing_files' . DS . 'gd' . DS);
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        Configure::write(THUMBER . '.driver', 'imagick');
        Configure::write(THUMBER . '.comparingDir', TESTS . DS . 'comparing_files' . DS . 'imagick' . DS);
    }
}
