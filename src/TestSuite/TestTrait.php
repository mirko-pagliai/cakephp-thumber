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

use Thumber\Cake\Utility\ThumbCreator;

/**
 * TestCase trait
 */
trait TestTrait
{
    /**
     * Returns an instance of `ThumbCreator`
     * @param string $path Path of the image from which to create the thumbnail. It can be a relative path (to `
     *  APP/webroot/img`), a full path or a remote url
     * @return \Thumber\Cake\Utility\ThumbCreator
     */
    protected function getThumbCreatorInstance(string $path = ''): ThumbCreator
    {
        return new ThumbCreator($path ?: '400x400.jpg');
    }
}
