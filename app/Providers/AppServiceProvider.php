<?php

namespace App\Providers;

use App\Billing\FakeStripeGateway;
use App\Billing\GatewayInterface;
use App\Billing\StripeGateway;
use App\Page;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['request']->server->set('HTTPS', $this->app->environment() != 'local' and $this->app->environment() != 'testing');

        Page::moved(function ($page) {
            if ($page) {
                dispatch(new \App\Jobs\UpdatePagePath($page));
            }
        });

        Page::created(function ($page) {
            if ($page->isRoot()) {
                dispatch(new \App\Jobs\UpdatePagePath($page));
            }
        });

        Page::updated(function ($page) {
            if ($page->isDirty('slug') and $page->fresh()) {
                dispatch(new \App\Jobs\UpdatePagePath($page->fresh()));
            }
        });

        // \DB::listen(function ($query) {
        //     var_dump($query->sql, $query->bindings, $query->time);
        // });
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        // Bugsnag reporting
        if ($this->app->environment('production')) {
            $this->app->alias('bugsnag.multi', Log::class);
            $this->app->alias('bugsnag.multi', LoggerInterface::class);
        }

        $this->app->bind('Illuminate\Contracts\Auth\Registrar', 'App\Services\Registrar');

        // bind a fake payment gateway if the environment is testing
        $this->app->singleton(GatewayInterface::class, function () {
            if ($this->app->environment('testing')) {
                return new FakeStripeGateWay();
            }

            return new StripeGateway();
        });

        $this->app->singleton(\AlgoliaSearch\Client::class, function () {
            return new \AlgoliaSearch\Client(config('scout.algolia.id'), config('scout.algolia.secret'));
        });
    }
}
