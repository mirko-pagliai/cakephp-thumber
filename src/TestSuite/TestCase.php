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

use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\TestSuite\TestCase as CakeTestCase;
use Thumber\ThumbsPathTrait;
use Tools\ReflectionTrait;
use Tools\TestSuite\TestCaseTrait;

/**
 * Thumber TestCase class
 */
abstract class TestCase extends CakeTestCase
{
    use ReflectionTrait;
    use TestCaseTrait;
    use ThumbsPathTrait;

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        foreach (glob($this->getPath() . DS . '*') as $file) {
            //@codingStandardsIgnoreLine
            @unlink($file);
        }
    }

    /**
     * Internal method to create a copy of an image file
     * @param string $path Image file path
     * @return string
     */
    protected static function createCopy($path)
    {
        $result = tempnam(sys_get_temp_dir(), $path);

        copy($path, $result);

        return $result;
    }

    /**
     * Asserts that the contents of one image file is equal to the contents of
     *  another image file
     * @param string $expected Expected file
     * @param string $actual Actual file
     * @param string $message The failure message that will be appended to the
     *  generated message
     * @return void
     * @uses createCopy()
     */
    public static function assertImageFileEquals($expected, $actual, $message = '')
    {
        if (!Folder::isAbsolute($expected)) {
            $expected = Configure::read(THUMBER . '.comparingDir') . $expected;
        }

        self::assertFileExists($expected, $message);
        self::assertFileExists($actual, $message);

        $expectedCopy = self::createCopy($expected);
        $actualCopy = self::createCopy($actual);

        self::assertFileEquals($expectedCopy, $actualCopy, $message);

        //@codingStandardsIgnoreStart
        @unlink($expectedCopy);
        @unlink($actualCopy);
        //@codingStandardsIgnoreEnd
    }

    /**
     * Asserts for a valid thumbnail path
     * @param string $path Thumbnail path
     * @param string $message The failure message that will be appended to the
     *  generated message
     * @return void
     * @since 1.1.1
     */
    public function assertThumbPath($path, $message = '')
    {
        $regex = sprintf('/^%s[\w\d]{32}_[\w\d]{32}\.\w{3,4}/', preg_quote($this->getPath() . DS, '/'));
        self::assertRegExp($regex, $path, $message);
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
     * Skips the test if you running the designated driver
     * @param string $driver Driver name
     * @param string $message The message to display
     * @return bool
     * @since 1.5.0
     */
    public function skipIfDriverIs($driver, $message = '')
    {
        return parent::skipIf(Configure::readOrFail(THUMBER . '.driver') == $driver, $message);
    }
}
