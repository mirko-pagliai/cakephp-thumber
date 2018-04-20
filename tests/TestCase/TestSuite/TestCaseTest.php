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
namespace Thumber\Test\TestCase\TestSuite;

use Cake\Core\Configure;
use Thumber\TestSuite\TestCase;
use Thumber\ThumbsPathTrait;
use Thumber\Utility\ThumbManager;

/**
 * TestCaseTest class
 */
class TestCaseTest extends TestCase
{
    use ThumbsPathTrait;

    /**
     * Test for `assertImageFileEquals()` method
     * @ŧest
     */
    public function testAssertImageFileEquals()
    {
        $original = Configure::read(THUMBER . '.comparingDir') . 'resize_w200_h200.jpg';
        $copy = tempnam(TMP, $original);

        copy($original, $copy);

        $this->assertImageFileEquals(Configure::read(THUMBER . '.comparingDir') . 'resize_w200_h200.jpg', $copy);
        $this->assertImageFileEquals('resize_w200_h200.jpg', $copy);
    }

    /**
     * Test for `assertThumbPath()` method
     * @ŧest
     */
    public function testAssertThumbPath()
    {
        foreach (ThumbManager::$supportedFormats as $extension) {
            $this->assertThumbPath($this->getPath() . DS . md5(time()) . '_' . md5(time()) . '.' . $extension);
        }
    }
}
