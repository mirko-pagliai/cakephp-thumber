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
use Tools\Filesystem;

//Default thumbnails driver
if (!defined('THUMBER_DRIVER')) {
    define('THUMBER_DRIVER', Configure::read('Thumber.driver', extension_loaded('imagick') ? 'imagick' : 'gd'));
}

//Default thumbnails directory
if (!defined('THUMBER_TARGET')) {
    define('THUMBER_TARGET', Configure::read('Thumber.target', TMP . 'thumbs'));
}

$bootstrap = (new Filesystem())->concatenate(ROOT, 'vendor', 'mirko-pagliai', 'php-thumber', 'config', 'bootstrap.php');
require_once $bootstrap;
