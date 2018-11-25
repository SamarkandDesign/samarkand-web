<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Fideloper\Proxy\TrustProxies',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'           => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic'     => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
        'guest'          => 'App\Http\Middleware\RedirectIfAuthenticated',
        'admin'          => 'App\Http\Middleware\ForbidIfNotAdmin',
        'bindings'       => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'throttle'       => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'order.session'  => \App\Http\Middleware\OrderMustBeInSession::class,
        'cart.empty'     => \App\Http\Middleware\RedirectIfCartIsEmpty::class,
        'can'            => \Illuminate\Auth\Middleware\Authorize::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'api' => [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            'throttle:60,1',
            'bindings',
        ],
    ];
}
