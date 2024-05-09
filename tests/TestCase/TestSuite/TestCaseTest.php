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

namespace Thumber\Cake\Test\TestCase\TestSuite;

use Cake\Core\Configure;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestStatus\Skipped;
use PHPUnit\Framework\TestStatus\Success;
use Thumber\Cake\Test\TestCase\SkipTestCase;
use Thumber\Cake\TestSuite\TestCase;
use Tools\Filesystem;

/**
 * TestCaseTest
 * @uses \Thumber\Cake\TestSuite\TestCase
 */
class TestCaseTest extends TestCase
{
    /**
     * @var \Thumber\Cake\TestSuite\TestCase
     */
    protected TestCase $TestCase;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->TestCase ??= new class ('myTest') extends TestCase {
        };
    }

    /**
     * @test
     * @uses \Thumber\Cake\TestSuite\TestCase::assertImageFileEquals()
     */
    public function testAssertImageFileEquals(): void
    {
        $image = THUMBER_COMPARING_DIR . 'resize_w200_h200.jpg';
        $copy = $this->createCopy($image);
        $this->assertImageFileEquals($copy, $image);

        $badCopy = $this->createCopy(THUMBER_COMPARING_DIR . 'resize_w400_h400.jpg');
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('The file `' . rtr($badCopy) . '` is not what you expected');
        $this->assertImageFileEquals($badCopy, $image);
    }

    /**
     * @test
     * @uses \Thumber\Cake\TestSuite\TestCase::assertImageSize()
     */
    public function testAssertImageSize(): void
    {
        $image = THUMBER_COMPARING_DIR . 'resize_w200_h200.jpg';

        $this->assertImageSize(200, 200, $image);

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that 300 matches expected 200');
        $this->assertImageSize(200, 300, $image);
    }

    /**
     * @test
     * @uses \Thumber\Cake\TestSuite\TestCase::assertThumbPath()
     */
    public function testAssertThumbPath(): void
    {
        $Filesystem = new Filesystem();

        $this->assertThumbPath($Filesystem->concatenate(Configure::readOrFail('Thumber.target'), '5426d23e4b4cb4fff73345b634542ba6_50c4f5a3a06310d4100e8815228cab76.png'));

        $this->expectException(AssertionFailedError::class);
        $this->assertThumbPath($Filesystem->concatenate(Configure::readOrFail('Thumber.target'), 'invalid'));
    }

    /**
     * @test
     * @uses \Thumber\Cake\TestSuite\TestCase::assertThumbUrl()
     */
    public function testAssertThumbUrl(): void
    {
        $this->assertThumbUrl('http://localhost/thumb/' . base64_encode(basename('5426d23e4b4cb4fff73345b634542ba6_50c4f5a3a06310d4100e8815228cab76.png')));

        $this->expectException(AssertionFailedError::class);
        $this->assertThumbUrl('http://localhost/thumb');
    }

    /**
     * @test
     * @uses \Thumber\Cake\TestSuite\TestCase::skipIfDriverIs()
     */
    public function testSkipIfDriverIs(): void
    {
        $expected = Configure::readOrFail('Thumber.driver') === 'imagick' ? ['gd' => false, 'imagick' => true] : ['imagick' => false, 'gd' => true];

        foreach ($expected as $driver => $expectedIsSkipped) {
            $Test = (new SkipTestCase('testSkipIfDriverIs' . ucfirst($driver)));
            $Test->run();
            $this->assertInstanceOf($expectedIsSkipped ? Skipped::class : Success::class, $Test->status());
        }
    }
}
