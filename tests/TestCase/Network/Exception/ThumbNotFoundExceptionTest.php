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
namespace Thumber\Test\TestCase\Network\Exception;

use Thumber\Network\Exception\ThumbNotFoundException;
use Thumber\TestSuite\TestCase;

/**
 * ThumbNotFoundExceptionTest class
 */
class ThumbNotFoundExceptionTest extends TestCase
{
    /**
     * Test for the exception
     * @expectedException Thumber\Network\Exception\ThumbNotFoundException
     * @expectedExceptionCode 404
     * @test
     * @throws ThumbNotFoundException
     */
    public function testException()
    {
        throw new ThumbNotFoundException;
    }

    /**
     * Test for the exception, with a message
     * @expectedException Thumber\Network\Exception\ThumbNotFoundException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Thumb not found!
     * @test
     * @throws ThumbNotFoundException
     */
    public function testExceptionWithMessage()
    {
        throw new ThumbNotFoundException('Thumb not found!');
    }
}
