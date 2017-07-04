<?php

namespace App\Providers;

use App\Page;
use App\Services\Geocoder\Geocoder;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;
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

        \App\Address::saved(function ($address) {
            // $location = $this->app->make(Geocoder::class)->getCoordinates($address);

            // $address->lat = $location->lat;
            // $address->lng = $location->lng;
            dispatch(new \App\Jobs\GeocodeAddress($address));
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

        $this->app->singleton(\AlgoliaSearch\Client::class, function () {
            return new \AlgoliaSearch\Client(config('scout.algolia.id'), config('scout.algolia.secret'));
        });

        $this->app->singleton(Geocoder::class, function () {
            if ($this->app->environment('testing')) {
                return new \App\Services\Geocoder\FakeGeocoder();
            }

            return $this->app->make(\App\Services\Geocoder\GoogleGeocoder::class);
        });

        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
