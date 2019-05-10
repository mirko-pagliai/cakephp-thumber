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
namespace Thumber\Test\TestCase\Command;

use Cake\Console\ConsoleIo;
use Cake\Console\Exception\StopException;
use Cake\TestSuite\Stub\ConsoleOutput;
use Exception;
use MeTools\TestSuite\ConsoleIntegrationTestTrait;
use Thumber\TestSuite\TestCase;
use Thumber\Utility\ThumbManager;

/**
 * ClearCommandTest class
 */
class ClearCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * @var bool
     */
    protected $autoInitializeClass = true;

    /**
     * Tests for `execute()` method
     * @test
     */
    public function testExecute()
    {
        $command = 'thumber.clear 400x400.jpg -v';

        $this->createSomeThumbs();
        $this->exec($command);
        $this->assertExitWithSuccess();
        $this->assertOutputContains('Thumbnails deleted: 2');

        $this->exec($command);
        $this->assertExitWithSuccess();
        $this->assertOutputContains('Thumbnails deleted: 0');

        $this->createSomeThumbs();
        $this->exec('thumber.clear 400x400.png -v');
        $this->assertExitWithSuccess();
        $this->assertOutputContains('Thumbnails deleted: 1');

        $this->exec('thumber.clear 400x400.png -v');
        $this->assertExitWithSuccess();
        $this->assertOutputContains('Thumbnails deleted: 0');

        //With full path
        $this->createSomeThumbs();
        $fullPath = WWW_ROOT . 'img' . DS . '400x400.jpg';
        $this->exec('thumber.clear ' . $fullPath . ' -v');
        $this->assertExitWithSuccess();
        $this->assertOutputContains('Thumbnails deleted: 2');

        $this->exec('thumber.clear ' . $fullPath . ' -v');
        $this->assertExitWithSuccess();
        $this->assertOutputContains('Thumbnails deleted: 0');
        $this->assertErrorEmpty();

        //With error
        $this->exec('thumber.clear ' . DS . 'noExisting -v');
        $this->assertExitWithError();
        $this->assertErrorContains('Error deleting thumbnails');

        //On failure
        $this->expectException(StopException::class);
        $this->Command->ThumbManager = $this->getMockBuilder(ThumbManager::class)
            ->setMethods(['_clear'])
            ->getMock();
        $this->Command->ThumbManager->method('_clear')
            ->will($this->throwException(new Exception()));

        $this->Command->run(['noExisting'], new ConsoleIo(null, new ConsoleOutput()));
    }
}
