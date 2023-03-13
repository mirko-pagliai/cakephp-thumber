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
 * ClearCommandTest class
 * @property \Thumber\Cake\Command\ClearCommand $Command
 */
class ClearCommandTest extends CommandTestCase
{
    use TestTrait;

    /**
     * @test
     * @uses \Thumber\Cake\Command\ClearCommand::execute()
     */
    public function testExecute(): void
    {
        $command = 'thumber.clear -v';

        $this->createSomeThumbs();
        $this->exec($command . ' 400x400.jpg');
        $this->assertExitSuccess();
        $this->assertOutputContains('Thumbnails deleted: 2');

        //With no thumbs
        $this->exec($command . ' 400x400.jpg');
        $this->assertExitSuccess();
        $this->assertOutputContains('Thumbnails deleted: 0');

        //With full path
        $this->createSomeThumbs();
        $this->exec($command . ' ' . WWW_ROOT . 'img' . DS . '400x400.jpg');
        $this->assertExitSuccess();
        $this->assertOutputContains('Thumbnails deleted: 2');

        //With a no existing file
        $this->exec($command . ' ' . DS . 'noExisting');
        $this->assertExitError();
        $this->assertErrorContains('Error deleting thumbnails');

        //On failure
        $this->expectException(StopException::class);
        $this->Command->ThumbManager = $this->createPartialMock(ThumbManager::class, ['_clear']);
        $this->Command->ThumbManager->method('_clear')->willThrowException(new Exception());
        $this->Command->run(['noExisting'], new ConsoleIo(null, new StubConsoleOutput()));
    }
}
