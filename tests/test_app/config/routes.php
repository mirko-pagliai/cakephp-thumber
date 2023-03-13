<?php
declare(strict_types=1);

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->scope('/', function ($routes) {
    $routes->loadPlugin('Thumber/Cake');
});
