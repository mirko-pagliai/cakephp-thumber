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
namespace Thumber\Test\TestCase;

use Cake\Core\Configure;
use Thumber\TestSuite\TestCase;
use Thumber\ThumbTrait;

/**
 * ThumbTraitTest class
 */
class ThumbTraitTest extends TestCase
{
    use ThumbTrait;

    /**
     * Test for `getPath()` method
     * @test
     */
    public function testGetPath()
    {
        $this->assertEquals(Configure::readOrFail(THUMBER . '.target'), $this->getPath());
        $this->assertEquals(Configure::readOrFail(THUMBER . '.target') . DS . 'file.jpg', $this->getPath('file.jpg'));
    }
}
