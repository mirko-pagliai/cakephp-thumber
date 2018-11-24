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
 * @since       1.6.1
 */
namespace Thumber\TestSuite;

use Cake\Http\BaseApplication;
use Cake\TestSuite\ConsoleIntegrationTestCase as CakeConsoleIntegrationTestCase;
use Thumber\Utility\ThumbCreator;

/**
 * Thumber TestCase class
 */
abstract class ConsoleIntegrationTestCase extends CakeConsoleIntegrationTestCase
{
    /**
     * Internal method to create some thumbs
     * @return void
     */
    protected function createSomeThumbs()
    {
        (new ThumbCreator('400x400.jpg'))->resize(200)->save();
        (new ThumbCreator('400x400.jpg'))->resize(300)->save();
        (new ThumbCreator('400x400.png'))->resize(200)->save();
    }

    /**
     * Called before every test method
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $app = $this->getMockForAbstractClass(BaseApplication::class, ['']);
        $app->addPlugin('Thumber')->pluginBootstrap();
    }
}
