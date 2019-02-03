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
use Thumber\ThumbTrait;
use Thumber\Utility\ThumbCreator;
use Tools\ReflectionTrait;
use Tools\TestSuite\TestCaseTrait;

/**
 * Thumber TestCase class
 */
abstract class TestCase extends CakeTestCase
{
    use ReflectionTrait;
    use TestCaseTrait {
        assertFileMime as baseAssertFileMime;
    }
    use ThumbTrait;

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        @unlink_recursive(Configure::readOrFail(THUMBER . '.target'));

        parent::tearDown();
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
     * Internal method to create some thumbs
     * @return void
     */
    protected function createSomeThumbs()
    {
        (new ThumbCreator('400x400.jpg'))->resize(200)->save();
        (new ThumbCreator('400x400.jpg'))->resize(300)->save();
        (new ThumbCreator('400x400.png'))->resize(200)->save();
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

        @unlink($expectedCopy);
        @unlink($actualCopy);
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
        $regex = sprintf(
            '/^%s[a-z0-9]{32}_[a-z0-9]{32}\.(%s)/',
            preg_quote($this->getPath() . DS, '/'),
            implode('|', self::getSupportedFormats())
        );
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
        self::assertRegExp('/^(http:\/\/localhost)?\/thumb\/[A-z0-9]+/', $url, $message);
    }
}
