<?php

namespace App;

use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\RoutingMiddleware;

class Application extends BaseApplication
{
    public function bootstrap()
    {
    }

    public function middleware($middlewareQueue)
    {
        return $middlewareQueue->add(new RoutingMiddleware($this));
    }
}
