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
 * @since       1.7.0
 */
namespace Thumber\TestSuite;

use Cake\TestSuite\ConsoleIntegrationTestTrait as CakeConsoleIntegrationTestTrait;

/**
 * A trait intended to make integration tests of cake console commands easier
 */
trait ConsoleIntegrationTestTrait
{
    use CakeConsoleIntegrationTestTrait;

    /**
     * Called before every test method
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->useCommandRunner();
    }
}
