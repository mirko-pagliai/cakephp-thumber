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
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::plugin(THUMBER, ['path' => '/thumb'], function (RouteBuilder $routes) {
    $routes->get('/:basename', ['controller' => 'Thumbs', 'action' => 'thumb'], 'thumb')
        ->setPatterns(['basename' => '[\w\d=]+'])
        ->setPass(['basename']);
});
