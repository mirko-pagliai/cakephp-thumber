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
use Cake\TestSuite\Stub\ConsoleOutput;
use Exception;
use MeTools\TestSuite\ConsoleIntegrationTestTrait;
use Thumber\Cake\TestSuite\TestCase;
use Thumber\Cake\Utility\ThumbManager;

/**
 * ClearCommandTest class
 * @property \Thumber\Cake\Command\ClearCommand $Command
 */
class ClearCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * @var bool
     */
    protected $autoInitializeClass = true;

    /**
     * @var string
     */
    protected $command = 'thumber.clear -v';

    /**
     * Tests for `execute()` method
     * @test
     */
    public function testExecute(): void
    {
        $this->createSomeThumbs();
        $this->exec($this->command . ' 400x400.jpg');
        $this->assertExitWithSuccess();
        $this->assertOutputContains('Thumbnails deleted: 2');
    }

    /**
     * Tests for `execute()` method, with no thumbs
     * @test
     */
    public function testExecuteNoThumbs(): void
    {
        $this->exec($this->command . ' 400x400.jpg');
        $this->assertExitWithSuccess();
        $this->assertOutputContains('Thumbnails deleted: 0');
    }

    /**
     * Tests for `execute()` method, with full path
     * @test
     */
    public function testExecuteWithFullPath(): void
    {
        $this->createSomeThumbs();
        $fullPath = WWW_ROOT . 'img' . DS . '400x400.jpg';
        $this->exec($this->command . ' ' . $fullPath);
        $this->assertExitWithSuccess();
        $this->assertOutputContains('Thumbnails deleted: 2');
        $this->assertErrorEmpty();
    }

    /**
     * Tests for `execute()` method, with a no existing file
     * @test
     */
    public function testExecuteNoExistingFile(): void
    {
        $this->exec($this->command . ' ' . DS . 'noExisting');
        $this->assertExitWithError();
        $this->assertErrorContains('Error deleting thumbnails');
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

        $this->Command->run(['noExisting'], new ConsoleIo(null, new ConsoleOutput()));
    }
}
