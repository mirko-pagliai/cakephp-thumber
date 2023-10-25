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
namespace Thumber\Cake\TestSuite;

use Cake\TestSuite\TestCase as BaseTestCase;
use Tools\Filesystem;

/**
 * TestCase class
 */
abstract class TestCase extends BaseTestCase
{
    use TestTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadPlugins(['Thumber\\Cake\\Plugin' => []]);
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        Filesystem::instance()->unlinkRecursive(THUMBER_TARGET);

        parent::tearDown();
    }
}
