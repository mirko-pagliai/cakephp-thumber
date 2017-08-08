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
use Thumber\Test\TestCase\Utility\ThumbCreatorFormatsTest as BaseThumbCreatorFormatsTest;

/**
 * ThumbCreatorFormatsTest class.
 *
 * These tests use the GD library.
 */
class ThumbCreatorFormatsTest extends BaseThumbCreatorFormatsTest
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
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        Configure::write(THUMBER . '.driver', 'imagick');
    }

    /**
     * Test for `save()` method, using a bmp file
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Unable to read image from file `tests/test_app/webroot/img/400x400.bmp`
     * @test
     */
    public function testSaveBmp()
    {
        parent::testSaveBmp();
    }

    /**
     * Test for `save()` method, using a ico file
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Unable to read image from file `tests/test_app/webroot/img/400x400.ico`
     * @test
     */
    public function testSaveIco()
    {
        parent::testSaveIco();
    }

    /**
     * Test for `save()` method, using a psd file
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Unable to read image from file `tests/test_app/webroot/img/400x400.psd`
     * @test
     */
    public function testSavePsd()
    {
        parent::testSavePsd();
    }

    /**
     * Test for `save()` method, using a tif file
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Unable to read image from file `tests/test_app/webroot/img/400x400.tif`
     * @test
     */
    public function testSaveTif()
    {
        parent::testSaveTif();
    }

    /**
     * Test for `save()` method, using a tiff file
     * @expectedException Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Unable to read image from file `tests/test_app/webroot/img/400x400.tiff`
     * @test
     */
    public function testSaveTiff()
    {
        parent::testSaveTiff();
    }
}
