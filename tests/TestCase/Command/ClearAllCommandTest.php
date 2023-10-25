<?php
/** @noinspection PhpUnhandledExceptionInspection */
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
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Console\TestSuite\StubConsoleOutput;
use Exception;
use Thumber\Cake\Command\ClearAllCommand;
use Thumber\Cake\TestSuite\TestCase;
use Thumber\Cake\TestSuite\TestTrait;
use Thumber\Cake\Utility\ThumbManager;

/**
 * ClearAllCommandTest class
 * @property \Thumber\Cake\Command\ClearAllCommand $Command
 */
class ClearAllCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use TestTrait;

    /**
     * @test
     * @uses \Thumber\Cake\Command\ClearAllCommand::execute()
     */
    public function testExecute(): void
    {
        $this->useCommandRunner();

        $command = 'thumber.clear_all -v';

        $this->createSomeThumbs();
        $this->exec($command);
        $this->assertExitSuccess();
        $this->assertOutputRegExp('/^Thumbnails deleted: [^0]\d*$/');

        //With no thumbnails
        $this->exec($command);
        $this->assertExitSuccess();
        $this->assertOutputContains('Thumbnails deleted: 0');
    }

    /**
     * Test for `execute()` method, on failure
     * @test
     * @uses \Thumber\Cake\Command\ClearAllCommand::execute()
     */
    public function testExecuteOnFailure(): void
    {
        $this->expectException(StopException::class);
        $ThumbManager = $this->createPartialMock(ThumbManager::class, ['_clear']);
        $ThumbManager->method('_clear')->willThrowException(new Exception());
        $Command = $this->createPartialMock(ClearAllCommand::class, ['getThumbManager']);
        $Command->method('getThumbManager')->willReturn($ThumbManager);
        $Command->run(['noExisting'], new ConsoleIo(null, new StubConsoleOutput()));
    }
}
