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
namespace Thumber\Cake\TestSuite;

use Cake\TestSuite\TestCase as CakeTestCase;
use Thumber\Cake\Utility\ThumbCreator;
//use Thumber\TestSuite\TestTrait as ThumberTestTrait;
use Tools\TestSuite\ReflectionTrait;
use Tools\TestSuite\TestTrait as ToolsTestTrait;
use Thumber\TestSuite\TestTrait;
use Tools\TestSuite\BackwardCompatibilityTrait;

/**
 * TestCase class
 */
abstract class TestCase extends CakeTestCase
{
    use ReflectionTrait;
//    use ThumberTestTrait;
    use ToolsTestTrait;
    use BackwardCompatibilityTrait;
    use TestTrait;

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
        self::assertMatchesRegularExpression('/^(http:\/\/localhost)?\/thumb\/[\w\d]+/', $url, $message);
    }

    /**
     * Returns an instance of `ThumbCreator`
     * @param string $path Path of the image from which to create the
     *  thumbnail. It can be a relative path (to APP/webroot/img), a full path
     *  or a remote url
     * @return \Thumber\Cake\Utility\ThumbCreator
     */
    protected function getThumbCreatorInstance($path = '')
    {
        return new ThumbCreator($path ?: '400x400.jpg');
    }

    /**
     * Returns an instance of `ThumbCreator`, after calling `resize()` and
     *  `save()` methods.
     *
     * It can be called passing only the array of options as first argument.
     * @param string $path Path of the image from which to create the thumbnail.
     *  It can be a full path or a remote url
     * @param array $options Options for saving
     * @return \Thumber\Cake\Utility\ThumbCreator
     */
    protected function getThumbCreatorInstanceWithSave(string $path = '', array $options = []): ThumbCreator
    {
        $thumbCreator = $this->getThumbCreatorInstance($path);
        $thumbCreator->resize(200)->save($options);

        return $thumbCreator;
    }
}
