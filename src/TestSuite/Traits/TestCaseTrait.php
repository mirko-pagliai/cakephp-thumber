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
 * @since       1.6.2
 */
namespace Thumber\TestSuite\Traits;

use Cake\Core\Configure;
use Thumber\Utility\ThumbCreator;
use Tools\TestSuite\TestCaseTrait as ToolsTestCaseTrait;

/**
 * TestCaseTrait
 */
trait TestCaseTrait
{
    use ToolsTestCaseTrait;

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
     * Deletes all thumbnails
     * @return bool
     */
    protected function deleteAll()
    {
        return safe_unlink_recursive(Configure::readOrFail(THUMBER . '.target'));
    }
}
