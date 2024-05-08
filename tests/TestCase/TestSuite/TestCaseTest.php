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
use PHPUnit\Framework\TestStatus\Skipped;
use PHPUnit\Framework\TestStatus\Success;
use Thumber\Cake\Test\TestCase\SkipTestCase;
use Thumber\Cake\TestSuite\TestCase;

/**
 * TestCaseTest
 * @uses \Thumber\Cake\TestSuite\TestCase
 */
class TestCaseTest extends TestCase
{
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
