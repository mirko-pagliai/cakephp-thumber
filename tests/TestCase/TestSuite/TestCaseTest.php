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

/**
 * TestCaseTest class
 */
class TestCaseTest extends TestCase
{
    /**
     * Test for `assertImageFileEquals()` method
     * @ŧest
     */
    public function testAssertImageFileEquals()
    {
        $original = Configure::read(THUMBER . '.comparingDir') . 'resize_w200_h200.jpg';
        $copy = tempnam(TMP, $original);

        copy($original, $copy);

        $this->assertImageFileEquals($original, $copy);
    }

    /**
     * Test for `assertImageSize()` method
     * @ŧest
     */
    public function testAssertImageSize()
    {
        $file = Configure::read(THUMBER . '.comparingDir') . 'resize_w200_h300_noAspectRatio.jpg';

        $this->assertImageSize($file, 200, 300);
        $this->assertImageSize(
            $file,
            array_values(getimagesize($file))[0],
            array_values(getimagesize($file))[1]
        );
    }

    /**
     * Test for `assertMime()` method
     * @ŧest
     */
    public function testAssertMime()
    {
        $file = tempnam(TMP, 'test_file.txt');

        file_put_contents($file, 'this is a test file');

        $this->assertMime($file, mime_content_type($file));
        $this->assertMime($file, 'text/plain');

        unlink($file);
    }
}
