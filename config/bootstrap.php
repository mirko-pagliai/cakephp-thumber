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

use Cake\Core\Configure;

//Default thumbnails driver
if (!Configure::check('Thumber.driver')) {
    Configure::write('Thumber.driver', extension_loaded('imagick') ? 'imagick' : 'gd');
}

//Default thumbnails directory
if (!Configure::check('Thumber.target')) {
    Configure::write('Thumber.target', TMP . 'thumbs');
}
