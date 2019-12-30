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

use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Cake\TestSuite\Stub\ConsoleOutput;
use Cake\Utility\Inflector;
use Thumber\Cake\Shell\ThumberShell;
use Thumber\Cake\TestSuite\TestCase;

/**
 * ThumbManagerTest class
 */
class ThumberShellTest extends TestCase
{
    /**
     * @var \Thumber\Cake\Shell\ThumberShell
     */
    protected $ThumberShell;

    /**
     * @var \Cake\TestSuite\Stub\ConsoleOutput
     */
    protected $err;

    /**
     * @var \Cake\TestSuite\Stub\ConsoleOutput
     */
    protected $out;

    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     * @uses createSomeThumbs()
     */
    public function setUp()
    {
        parent::setUp();

        $this->createSomeThumbs();

        $this->out = new ConsoleOutput();
        $this->err = new ConsoleOutput();
        $io = new ConsoleIo($this->out, $this->err);
        $io->level(2);

        $this->ThumberShell = $this->getMockBuilder(ThumberShell::class)
            ->setMethods(['in', '_stop'])
            ->setConstructorArgs([$io])
            ->getMock();
    }

    /**
     * Tests for `clear()` method
     * @group onlyUnix
     * @test
     */
    public function testClear()
    {
        $this->ThumberShell->clear('400x400.jpg');
        $this->assertEquals(['Thumbnails deleted: 2'], $this->out->messages());
        $this->assertEmpty($this->err->messages());

        $this->setProperty($this->out, '_out', []);

        $this->ThumberShell->clear('400x400.jpg');
        $this->assertEquals(['Thumbnails deleted: 0'], $this->out->messages());
        $this->assertEmpty($this->err->messages());

        @unlink_recursive(THUMBER_TARGET);
        $this->createSomeThumbs();
        $this->setProperty($this->out, '_out', []);

        $this->ThumberShell->clear('400x400.png');
        $this->assertEquals(['Thumbnails deleted: 1'], $this->out->messages());
        $this->assertEmpty($this->err->messages());

        $this->setProperty($this->out, '_out', []);

        $this->ThumberShell->clear('400x400.png');
        $this->assertEquals(['Thumbnails deleted: 0'], $this->out->messages());
        $this->assertEmpty($this->err->messages());

        $this->createSomeThumbs();
        $this->setProperty($this->out, '_out', []);

        $fullPath = WWW_ROOT . 'img' . DS . '400x400.jpg';

        //With full path
        $this->ThumberShell->clear($fullPath);
        $this->assertEquals(['Thumbnails deleted: 2'], $this->out->messages());
        $this->assertEmpty($this->err->messages());

        $this->setProperty($this->out, '_out', []);

        $this->ThumberShell->clear($fullPath);
        $this->assertEquals(['Thumbnails deleted: 0'], $this->out->messages());
        $this->assertEmpty($this->err->messages());
    }

    /**
     * Tests for `clear()` method with error
     * @group onlyUnix
     * @test
     */
    public function testClearWithError()
    {
        $this->ThumberShell->clear(DS . 'noExisting');
        $this->assertEmpty($this->out->messages());
        $this->assertCount(1, $this->err->messages());
        $this->assertContains('Error deleting thumbnails', $this->err->messages()[0]);
    }

    /**
     * Tests for `clearAll()` method
     * @group onlyUnix
     * @test
     */
    public function testClearAll()
    {
        $this->ThumberShell->clearAll();
        $this->assertEquals(['Thumbnails deleted: 3'], $this->out->messages());
        $this->assertEmpty($this->err->messages());

        $this->setProperty($this->out, '_out', []);

        $this->ThumberShell->clearAll();
        $this->assertEquals(['Thumbnails deleted: 0'], $this->out->messages());
        $this->assertEmpty($this->err->messages());
    }

    /**
     * Test for `getOptionParser()` method
     * @test
     */
    public function testGetOptionParser()
    {
        $parser = (new ThumberShell())->getOptionParser();

        $this->assertInstanceOf('Cake\Console\ConsoleOptionParser', $parser);

        $subCommands = ['clear', 'clear_all'];

        if (version_compare(Configure::version(), '3.5', '<')) {
            $subCommands = array_map([Inflector::class, 'variable'], $subCommands);
        }

        $this->assertEquals($subCommands, array_keys($parser->subcommands()));

        $this->assertEquals('A shell to manage thumbnails', $parser->description());
    }
}
