<?php
/**
 * This file is part of cakephp-thumber.
 *
 * cakephp-thumber is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * cakephp-thumber is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with cakephp-thumber.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */
namespace Thumber\Test\TestCase\Network\Exception;

use Cake\TestSuite\TestCase;
use Thumber\Network\Exception\ThumbNotFoundException;

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
