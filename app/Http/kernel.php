<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Global middleware
    ];

    protected $middlewareGroups = [
        'web' => [
            // Web middleware
        ],
        'api' => [
            // API middleware
        ],
    ];

    protected $routeMiddleware = [
        // Route-specific middleware
        'auth' => \App\Http\Middleware\Authenticate::class,

    ];
}
