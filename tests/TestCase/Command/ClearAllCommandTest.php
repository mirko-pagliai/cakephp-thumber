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
namespace Test\TestCase\Command;

use Thumber\TestSuite\ConsoleIntegrationTestTrait;
use Thumber\TestSuite\TestCase;

/**
 * ClearAllCommandTest class
 */
class ClearAllCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * Tests for `execute()` method
     * @test
     */
    public function testExecute()
    {
        $this->createSomeThumbs();
        $this->exec('clear_all -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 3');

        $this->exec('clear_all -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 0');
    }
}
