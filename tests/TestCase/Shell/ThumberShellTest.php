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

use Cake\Http\BaseApplication;
use Cake\TestSuite\ConsoleIntegrationTestCase;
use Thumber\Shell\ThumberShell;
use Thumber\Utility\ThumbCreator;

/**
 * ThumbManagerTest class
 */
class ThumberShellTest extends ConsoleIntegrationTestCase
{
    /**
     * Internal method to create some thumbs
     */
    protected function createSomeThumbs()
    {
        (new ThumbCreator('400x400.jpg'))->resize(200)->save();
        (new ThumbCreator('400x400.jpg'))->resize(300)->save();
        (new ThumbCreator('400x400.png'))->resize(200)->save();
    }

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

        $app = $this->getMockForAbstractClass(BaseApplication::class, ['']);
        $app->addPlugin('Thumber')->pluginBootstrap();

        $this->createSomeThumbs();
    }

    /**
     * Tests for `clear()` method
     * @test
     */
    public function testClear()
    {
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

        $this->createSomeThumbs();

        $fullPath = WWW_ROOT . 'img' . DS . '400x400.jpg';

        //With full path
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

        $this->assertInstanceOf('Cake\Console\ConsoleOptionParser', $parser);
        $this->assertEquals(['clear', 'clear_all'], array_keys($parser->subcommands()));
        $this->assertEquals('A shell to manage thumbnails', $parser->getDescription());
    }
}
