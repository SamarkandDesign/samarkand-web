<?php

namespace App\Providers;

use App\Page;
use App\Post;
use App\Product;
use App\Term;
use Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::model('term', 'App\Term');
        Route::model('user', 'App\User');
        Route::model('post', 'App\Post');
        Route::model('media', 'App\Media');
        Route::model('address', 'App\Address');
        Route::model('page', 'App\Page');

        Route::bind('product', function ($id) {
            $products = $this->app->make(\App\Repositories\Product\ProductRepository::class);

            return $products->fetch($id, ['product_categories', 'media']);
        });

        Route::bind('order', function ($id) {
            return \App\Order::with(['items', 'shipping_address', 'billing_address'])->findOrFail($id);
        });

        Route::bind('trashedPost', function ($id) {
            return Post::withTrashed()->find($id);
        });

        Route::bind('trashedPage', function ($id) {
            return Page::withTrashed()->find($id);
        });

        Route::bind('trashedProduct', function ($id) {
            return Product::withTrashed()->find($id);
        });

        Route::bind('username', function ($username) {
            return \App\User::where('username', $username)->firstOrFail();
        });

        Route::bind('product_slug', function ($slug) {
            return Product::where('slug', $slug)->firstOrFail();
        });

        Route::bind('product_category', function ($slug) {
            return Term::where([
                'taxonomy' => 'product_category',
                'slug'     => $slug,
                ])->firstOrFail();
        });

        Route::bind('path', function ($path) {
            return Page::wherePath($path)->published()->firstOrFail();
        });
    }


    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();

        $this->mapAdminRoutes();

        $this->mapApiRoutes();

        /*
         * If none of the above routes are matched we will see if a page has a matching path
         */
        Route::get('{path}', [
            'uses' => "$this->namespace\PagesController@show", 
            'middleware' => ['web'],
            ])->where('path', '.+');
    }

    public function mapAdminRoutes()
    {
        Route::group([
            'middleware' => ['web', 'can:access-admin'],
            'namespace' => "{$this->namespace}\Admin",
            'prefix'    => 'admin'
        ], function ($router) {
            require base_path('routes/admin.php');
        });

        Route::group([
            'middleware' => ['web', 'guest'],
            'namespace' => "{$this->namespace}\Admin",
        ], function ($router) {
            $router->get('admin/login', [
                'uses' => 'AdminController@login',
                'as' => 'admin.login',
                ]);
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => "{$this->namespace}\Api",
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}
