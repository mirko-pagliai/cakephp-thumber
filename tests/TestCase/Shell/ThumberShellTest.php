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
namespace Thumber\Test\TestCase\Shell;

use Cake\Console\ConsoleOptionParser;
use Thumber\Shell\ThumberShell;
use Thumber\TestSuite\ConsoleIntegrationTestCase;
use Thumber\TestSuite\Traits\TestCaseTrait;

/**
 * ThumbManagerTest class
 */
class ThumberShellTest extends ConsoleIntegrationTestCase
{
    use TestCaseTrait;

    /**
     * Tests for `clear()` method
     * @test
     */
    public function testClear()
    {
        $this->createSomeThumbs();
        $this->exec('thumber.thumber clear 400x400.jpg -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 2');

        $this->exec('thumber.thumber clear 400x400.jpg -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 0');

        $this->createSomeThumbs();
        $this->exec('thumber.thumber clear 400x400.png -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 1');

        $this->exec('thumber.thumber clear 400x400.png -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 0');

        //With full path
        $fullPath = WWW_ROOT . 'img' . DS . '400x400.jpg';
        $this->createSomeThumbs();
        $this->exec('thumber.thumber clear ' . $fullPath . ' -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 2');

        $this->exec('thumber.thumber clear ' . $fullPath . ' -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 0');
    }

    /**
     * Tests for `clear()` method with error
     * @test
     */
    public function testClearWithError()
    {
        $this->createSomeThumbs();
        $this->exec('thumber.thumber clear ' . DS . 'noExisting -v');
        $this->assertExitCode(1);
        $this->assertErrorContains('Error deleting thumbnails');
    }

    /**
     * Tests for `clearAll()` method
     * @test
     */
    public function testClearAll()
    {
        $this->createSomeThumbs();
        $this->exec('thumber.thumber clear_all -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 3');

        $this->exec('thumber.thumber clear_all -v');
        $this->assertExitCode(0);
        $this->assertOutputContains('Thumbnails deleted: 0');
    }

    /**
     * Test for `getOptionParser()` method
     * @test
     */
    public function testGetOptionParser()
    {
        $parser = (new ThumberShell)->getOptionParser();

        $this->assertInstanceOf(ConsoleOptionParser::class, $parser);
        $this->assertArrayKeysEqual(['clear', 'clear_all'], $parser->subcommands());
        $this->assertEquals('A shell to manage thumbnails', $parser->getDescription());
    }
}
