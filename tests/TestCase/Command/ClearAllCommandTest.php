<?php
declare(strict_types=1);

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
namespace Thumber\Cake\Test\TestCase\Command;

use Cake\Console\ConsoleIo;
use Cake\Console\Exception\StopException;
use Cake\Console\TestSuite\StubConsoleOutput;
use Exception;
use MeTools\TestSuite\ConsoleIntegrationTestTrait;
use Thumber\Cake\TestSuite\TestCase;
use Thumber\Cake\Utility\ThumbManager;

/**
 * ClearAllCommandTest class
 * @property \Thumber\Cake\Command\ClearAllCommand $Command
 */
class ClearAllCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * @var bool
     */
    protected $autoInitializeClass = true;

    /**
     * @var string
     */
    protected $command = 'thumber.clear_all -v';

    /**
     * Tests for `execute()` method
     * @test
     */
    public function testExecute(): void
    {
        $this->createSomeThumbs();
        $this->exec($this->command);
        $this->assertExitWithSuccess();
        $this->assertOutputRegExp('/^Thumbnails deleted: [^0]\d*$/');
    }

    /**
     * Tests for `execute()` method, with no thumbnails
     * @test
     */
    public function testExecuteNoThumbs(): void
    {
        $this->exec($this->command);
        $this->assertExitWithSuccess();
        $this->assertOutputContains('Thumbnails deleted: 0');
    }

    /**
     * Tests for `execute()` method, on failure
     * @test
     */
    public function testExecuteOnFailure(): void
    {
        $this->expectException(StopException::class);
        $this->Command->ThumbManager = $this->getMockBuilder(ThumbManager::class)
            ->setMethods(['_clear'])
            ->getMock();
        $this->Command->ThumbManager->method('_clear')
            ->will($this->throwException(new Exception()));

        $this->Command->run([], new ConsoleIo(null, new StubConsoleOutput()));
    }
}
