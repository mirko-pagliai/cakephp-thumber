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
namespace Thumber\TestSuite;

use Exception;
use MeTools\TestSuite\TestCase as BaseTestCase;
use Thumber\Utility\ThumbCreator;
use Tools\Thumber\TestSuite\TestTrait;

/**
 * TestCase class
 */
abstract class TestCase extends BaseTestCase
{
    use TestTrait;

    /**
     * Called after every test method
     * @return void
     */
    public function tearDown()
    {
        try {
            unlink_recursive(THUMBER_TARGET);
        } catch (Exception $e) {
        }

        parent::tearDown();
    }

    /**
     * Asserts for a valid thumbnail url
     * @param string $url Thumbnail url
     * @param string $message The failure message that will be appended to the
     *  generated message
     * @return void
     * @since 1.4.0
     */
    public function assertThumbUrl($url, $message = '')
    {
        self::assertRegExp('/^(http:\/\/localhost)?\/thumb\/[\w\d]+/', $url, $message);
    }

    /**
     * Returns an instance of `ThumbCreator`
     * @param string|null $path Path of the image from which to create the
     *  thumbnail. It can be a relative path (to APP/webroot/img), a full path
     *  or a remote url
     * @return \Thumber\Utility\ThumbCreator
     * @since 1.5.1
     */
    protected function getThumbCreatorInstance($path = null)
    {
        return new ThumbCreator($path ?: '400x400.jpg');
    }
}
