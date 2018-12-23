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
 */
use Cake\Core\Configure;

//Default thumbnails driver
if (!Configure::check('Thumber.driver')) {
    Configure::write('Thumber.driver', 'imagick');
}

//Default thumbnails directory
if (!Configure::check('Thumber.target')) {
    Configure::write('Thumber.target', TMP . 'thumbs');
}

//Checks for driver
$driver = Configure::read('Thumber.driver');
if (!in_array($driver, ['imagick', 'gd'])) {
    trigger_error(sprintf('The driver `%s` is not supported', $driver), E_USER_ERROR);
}

//Checks for target directory
$target = Configure::read('Thumber.target');
safe_mkdir($target, 0777, true);

if (!is_writeable($target)) {
    trigger_error(sprintf('Directory `%s` not writeable', $target), E_USER_ERROR);
}
