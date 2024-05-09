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

use Cake\Cache\Cache;
use Cake\Core\Configure;

ini_set('intl.default_locale', 'en_US');
date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

define('ROOT', dirname(__DIR__) . DS);
const CORE_PATH = ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS;
const TESTS = ROOT . 'tests' . DS;
const APP = ROOT . 'tests' . DS . 'test_app' . DS;
const WWW_ROOT = APP . 'webroot' . DS;
define('TMP', sys_get_temp_dir() . DS . 'cakephp-thumber' . DS);
const CONFIG = APP . 'config' . DS;

if (!file_exists(TMP)) {
    mkdir(TMP, 0777, true);
}

require dirname(__DIR__) . '/vendor/autoload.php';
require CORE_PATH . 'config' . DS . 'bootstrap.php';

Configure::write('debug', true);
Configure::write('App', [
    'namespace' => 'App',
    'encoding' => 'UTF-8',
    'base' => false,
    'baseUrl' => false,
    'dir' => 'test_app' . DS,
    'webroot' => 'webroot',
    'wwwRoot' => WWW_ROOT,
    'fullBaseUrl' => 'http://localhost',
    'imageBaseUrl' => 'img/',
    'jsBaseUrl' => 'js/',
    'cssBaseUrl' => 'css/',
    'paths' => ['plugins' => [APP . 'Plugin' . DS]],
]);
Cache::setConfig([
    '_cake_core_' => [
        'engine' => 'File',
        'prefix' => 'cake_core_',
        'serialize' => true,
    ],
]);

if (getenv('THUMBER_DRIVER')) {
    Configure::write('Thumber.driver', getenv('THUMBER_DRIVER'));
}
require_once ROOT . 'config' . DS . 'bootstrap.php';

define('THUMBER_COMPARING_DIR', TESTS . 'examples' . DS . 'comparing_files' . DS . Configure::readOrFail('Thumber.driver') . DS);

$_SERVER['PHP_SELF'] = '/';

echo 'Running tests for "' . Configure::readOrFail('Thumber.driver') . '" driver ' . PHP_EOL;
