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
 * @since       1.3.0
 */
namespace Thumber\Utility;

use PhpThumber\ThumbManager as PhpThumberThumbManager;
use Thumber\ThumbsPathTrait;

/**
 * A utility to manage thumbnails
 */
class ThumbManager extends PhpThumberThumbManager
{
    use ThumbsPathTrait;
}
