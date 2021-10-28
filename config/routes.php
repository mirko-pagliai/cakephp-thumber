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

use Cake\Routing\RouteBuilder;
use Thumber\Cake\Middleware\ThumbnailMiddleware;

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->plugin('Thumber', ['path' => '/thumb'], function (RouteBuilder $routes) {
    $routes->registerMiddleware('thumbnail', new ThumbnailMiddleware());

    if (!$routes->nameExists('thumb')) {
        $routes->get('/{basename}', [], 'thumb')
            ->setPatterns(['basename' => '[\w\d=]+'])
            ->setPass(['basename'])
            ->setMiddleware(['thumbnail']);
    }
});
