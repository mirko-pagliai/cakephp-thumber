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
use Thumber\Utility\ThumbCreator;
use Tools\ReflectionTrait;
use Tools\TestSuite\TestCaseTrait;

/**
 * TestCase class
 */
abstract class TestCase extends CakeTestCase
{
    use ReflectionTrait;
    use TestCaseTrait;
    use ThumbsPathTrait;

    /**
     * Called before every test method
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadPlugins(['Thumber']);
    }

    /**
     * Called after every test method
     * @return void
     */
    public function tearDown()
    {
        $this->deleteAll();

        parent::tearDown();
    }

    /**
     * Internal method to create a copy of an image file
     * @param string $path Image file path
     * @return string
     */
    protected static function createCopy($path)
    {
        $result = create_tmp_file();
        @copy($path, $result);

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
     * Deletes all thumbnails
     * @return bool
     */
    protected function deleteAll()
    {
        return @unlink_recursive(Configure::readOrFail('Thumber.target'));
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
        $expected = Folder::isAbsolute($expected) ? $expected : Configure::read('Thumber.comparingDir') . $expected;
        self::assertFileExists($expected, $message);
        self::assertFileExists($actual, $message);

        $expectedCopy = self::createCopy($expected);
        $actualCopy = self::createCopy($actual);
        self::assertFileEquals($expectedCopy, $actualCopy, $message);

        @array_map('unlink', [$expectedCopy, $actualCopy]);
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
        $regex = sprintf('/^%s[\w\d_]+\.\w{3,4}/', preg_quote($this->getPath() . DS, '/'));
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
     * Returns an instance of `ThumbCreator`
     * @param string $path Path of the image from which to create the
     *  thumbnail. It can be a relative path (to APP/webroot/img), a full path
     *  or a remote url
     * @return ThumbCreator
     * @since 1.5.1
     */
    protected function getThumbCreatorInstance($path = null)
    {
        return new ThumbCreator($path ?: '400x400.jpg');
    }

    /**
     * Returns an instance of `ThumbCreator`, after calling `resize()` and
     *  `save()` methods.
     *
     * It can be called passing only the array of options as first argument.
     * @param string $path Path of the image from which to create the
     *  thumbnail. It can be a relative path (to APP/webroot/img), a full path
     *  or a remote url
     * @param array $options Options for saving
     * @return ThumbCreator
     * @since 1.5.1
     * @uses getThumbCreatorInstance()
     */
    protected function getThumbCreatorInstanceWithSave($path = null, array $options = [])
    {
        if (is_array($path) && func_num_args() < 2) {
            list($options, $path) = [$path, null];
        }

        $thumbCreator = $this->getThumbCreatorInstance($path);
        $thumbCreator->resize(200)->save($options);

        return $thumbCreator;
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
        return parent::skipIf(Configure::readOrFail('Thumber.driver') == $driver, $message);
    }
}
