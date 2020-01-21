<?php
declare(strict_types=1);

use Cake\Routing\Router;

Router::scope('/', function ($routes) {
    $routes->loadPlugin('Thumber/Cake');
});
