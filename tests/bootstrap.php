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

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Routing\DispatcherFactory;

session_start();
ini_set('intl.default_locale', 'en_US');
date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

define('ROOT', dirname(__DIR__) . DS);
define('CAKE_CORE_INCLUDE_PATH', ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp');
define('CORE_PATH', ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS);
define('CAKE', CORE_PATH . 'src' . DS);
define('TESTS', ROOT . 'tests' . DS);
define('APP', ROOT . 'tests' . DS . 'test_app' . DS);
define('APP_DIR', 'test_app' . DS);
define('WEBROOT_DIR', 'webroot' . DS);
define('WWW_ROOT', APP . 'webroot' . DS);
define('TMP', sys_get_temp_dir() . DS . 'cakephp-thumber' . DS);
define('CONFIG', APP . 'config' . DS);
define('CACHE', TMP . 'cache' . DS);
define('LOGS', TMP . 'logs' . DS);
define('SESSIONS', TMP . 'sessions' . DS);
@mkdir(TMP);
@mkdir(LOGS);
@mkdir(SESSIONS);
@mkdir(CACHE);
@mkdir(CACHE . 'views');
@mkdir(CACHE . 'models');

require dirname(__DIR__) . '/vendor/autoload.php';
require CORE_PATH . 'config' . DS . 'bootstrap.php';

//Disables deprecation warnings for CakePHP 3.6
if (version_compare(Configure::version(), '3.6', '>=')) {
    error_reporting(E_ALL ^ E_USER_DEPRECATED);
}

Configure::write('debug', true);
Configure::write('App', [
    'namespace' => 'App',
    'encoding' => 'UTF-8',
    'base' => false,
    'baseUrl' => false,
    'dir' => APP_DIR,
    'webroot' => 'webroot',
    'wwwRoot' => WWW_ROOT,
    'fullBaseUrl' => 'http://localhost',
    'imageBaseUrl' => 'img/',
    'jsBaseUrl' => 'js/',
    'cssBaseUrl' => 'css/',
    'paths' => ['plugins' => [APP . 'Plugin' . DS]],
]);

Cache::config([
    '_cake_core_' => [
        'engine' => 'File',
        'prefix' => 'cake_core_',
        'serialize' => true,
    ],
]);

Configure::write('pluginsToLoad', ['Thumber/Cake']);
if (!getenv('THUMBER_DRIVER')) {
    putenv('THUMBER_DRIVER=' . (extension_loaded('imagick') ? 'imagick' : 'gd'));
}

Configure::write('Thumber.driver', getenv('THUMBER_DRIVER'));
define('THUMBER_EXAMPLE_DIR', ROOT . 'vendor' . DS . 'mirko-pagliai' . DS . 'php-thumber' . DS . 'tests' . DS . 'examples' . DS);
define('THUMBER_COMPARING_DIR', THUMBER_EXAMPLE_DIR . 'comparing_files' . DS . getenv('THUMBER_DRIVER') . DS);

Plugin::load('Thumber/Cake', [
    'bootstrap' => true,
    'path' => ROOT,
    'routes' => true,
]);

DispatcherFactory::add('Routing');
DispatcherFactory::add('ControllerFactory');

$_SERVER['PHP_SELF'] = '/';

if (function_exists('loadPHPUnitAliases')) {
    loadPHPUnitAliases();
}

echo 'Running tests for "' . getenv('THUMBER_DRIVER') . '" driver ' . PHP_EOL;
