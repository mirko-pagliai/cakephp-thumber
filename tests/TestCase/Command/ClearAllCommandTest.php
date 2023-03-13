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
use Cake\Console\TestSuite\StubConsoleOutput;
use Exception;
use MeTools\TestSuite\CommandTestCase;
use Thumber\Cake\TestSuite\TestTrait;
use Thumber\Cake\Utility\ThumbManager;

/**
 * ClearAllCommandTest class
 * @property \Thumber\Cake\Command\ClearAllCommand $Command
 */
class ClearAllCommandTest extends CommandTestCase
{
    use TestTrait;

    /**
     * @test
     * @uses \Thumber\Cake\Command\ClearAllCommand::execute()
     */
    public function testExecute(): void
    {
        $command = 'thumber.clear_all -v';

        $this->createSomeThumbs();
        $this->exec($command);
        $this->assertExitSuccess();
        $this->assertOutputRegExp('/^Thumbnails deleted: [^0]\d*$/');

        //With no thumbnails
        $this->exec($command);
        $this->assertExitSuccess();
        $this->assertOutputContains('Thumbnails deleted: 0');

        //On failure
        $this->expectException(StopException::class);
        $this->Command->ThumbManager = $this->createPartialMock(ThumbManager::class, ['_clear']);
        $this->Command->ThumbManager->method('_clear')->willThrowException(new Exception());
        $this->Command->run([], new ConsoleIo(null, new StubConsoleOutput()));
    }
}
