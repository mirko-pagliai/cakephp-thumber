<?php
/** @noinspection PhpDocMissingThrowsInspection,PhpUnhandledExceptionInspection */
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
namespace Thumber\Cake\Test\TestCase\Utility;

use Thumber\Cake\TestSuite\TestCase;
use Thumber\Cake\Utility\ThumbManager;
use Tools\TestSuite\ReflectionTrait;

/**
 * ThumbManagerTest class
 */
class ThumbManagerTest extends TestCase
{
    use ReflectionTrait;

    /**
     * @var \Thumber\Cake\Utility\ThumbManager
     */
    protected ThumbManager $ThumbManager;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        if (empty($this->ThumbManager)) {
            $this->ThumbManager = new ThumbManager();
        }

        $this->createSomeThumbs();
    }

    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbManager::resolveFilePath()
     */
    public function testResolveFilePath(): void
    {
        $result = $this->invokeMethod($this->ThumbManager, 'resolveFilePath', ['400x400.jpg']);
        $this->assertSame($result, WWW_ROOT . 'img' . DS . '400x400.jpg');

        $this->loadPlugins(['TestPlugin' => []]);
        $result = $this->invokeMethod($this->ThumbManager, 'resolveFilePath', ['TestPlugin.400x400.png']);
        $this->assertStringEndsWith('TestPlugin' . DS . 'webroot' . DS . 'img' . DS . '400x400.png', $result);
    }

    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbManager::get()
     */
    public function testGet(): void
    {
        $this->assertCount(2, $this->ThumbManager->get('400x400.jpg'));
        $this->assertCount(1, $this->ThumbManager->get('400x400.png'));
    }

    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbManager::clear()
     */
    public function testClear(): void
    {
        $this->assertEquals(2, $this->ThumbManager->clear('400x400.jpg'));
        $this->assertEquals(1, $this->ThumbManager->clear('400x400.png'));

        $this->createSomeThumbs();
        $this->assertEquals(1, $this->ThumbManager->clear(WWW_ROOT . 'img' . DS . '400x400.png'));
    }

    /**
     * @test
     * @uses \Thumber\Cake\Utility\ThumbManager::clearAll()
     */
    public function testClearAll(): void
    {
        $this->assertEquals(3, $this->ThumbManager->clearAll());
        $this->assertEmpty($this->ThumbManager->getAll());
    }
}
