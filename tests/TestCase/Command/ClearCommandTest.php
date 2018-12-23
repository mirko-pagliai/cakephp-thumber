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
 * ClearCommandTest class
 */
class ClearCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * Tests for `execute()` method
     * @test
     */
    public function testExecute()
    {
        $command = 'thumber.clear 400x400.jpg -v';

        $this->createSomeThumbs();
        $this->exec($command);
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 2');

        $this->exec($command);
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 0');

        $this->createSomeThumbs();
        $this->exec('thumber.clear 400x400.png -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 1');

        $this->exec('thumber.clear 400x400.png -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 0');

        //With full path
        $this->createSomeThumbs();
        $fullPath = WWW_ROOT . 'img' . DS . '400x400.jpg';
        $this->exec('thumber.clear ' . $fullPath . ' -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 2');

        $this->exec('thumber.clear ' . $fullPath . ' -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 0');

        //With error
        $this->exec('thumber.clear ' . DS . 'noExisting -v');
        $this->assertExitCode(1);
        $this->assertErrorContains('Error deleting thumbnails');
    }
}
