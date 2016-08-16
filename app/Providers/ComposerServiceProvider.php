<?php

namespace App\Providers;

use App\Countries\CountryRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Term\TermRepository;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->shareRoles();

        $this->sharePostData();

        $this->shareProductIndexSettings();

        $this->shareOrderCountWithSidebar();

        $this->shareAttributes();

        $this->shareProductCategories();

        $this->shareSearchKey();
    }

    /**
     * Build up a special search key to be used just for search products on the front end.
     *
     * @return void [description]
     */
    private function shareSearchKey()
    {
        $this->app->view->composer('shop._product_search', function ($view) {
            $expiry = Carbon::now()->addWeek();
            $searchKey = $this->app->cache->remember('shop_search_key', $expiry->subDay(), function () use ($expiry) {
                $alogia = new \AlgoliaSearch\Client(
                        config('searchindex.algolia.application-id'),
                        config('searchindex.algolia.api-key')
                        );

                return $alogia->generateSecuredApiKey(config('searchindex.algolia.search-only-api-key'), [
                    'filters'    => 'listed:true',
                    'validUntil' => $expiry->timestamp,
                    ]);
            });

            $view->with(compact('searchKey'));
        });
    }

    private function shareProductCategories()
    {
        $this->app->view->composer('shop._category_filter', function ($view) {
            $terms = $this->app->make(TermRepository::class);
            $view->with(['product_categories' => $terms->getTerms('product_category')]);
        });
    }

    /**
     * Share a list of roles with the views that require it.
     *
     * @return void
     */
    private function shareRoles()
    {
        $this->app->view->composer('admin.users.form', function ($view) {
            $roles = \App\Role::lists('display_name', 'id');

            $view->with(compact('roles'));
        });
    }

    /**
     * Share Post data with views.
     *
     * @return void
     */
    private function sharePostData()
    {
        $this->app->view->composer('admin.posts.index', \App\Composers\Admin\PostViewComposer::class.'@postCount');
    }

    private function shareCountries()
    {
        $this->app->view->composer(['shop.checkout', 'addresses.form'], function ($view) {
            $countries_repository = $this->app->make(CountryRepository::class);

            $view->with('countries', $countries_repository->lists());
        });
    }

    /**
     * Share configuration pertinant to displaying a list of products.
     *
     * @return void
     */
    private function shareProductIndexSettings()
    {
        $this->app->view->composer('shop.index', function ($view) {
            $view->with('products_per_row', config('shop.products_per_row'));
        });
    }

    private function shareOrderCountWithSidebar()
    {
        $this->app->view->composer('admin.layouts.sidebar-menu', function ($view) {
            $orders = $this->app->make(OrderRepository::class);
            $view->with('order_count', $orders->count(\App\Order::PAID));
        });
    }

    /**
     * Share the nested product attributes with the relevant views.
     *
     * @return void
     */
    private function shareAttributes()
    {
        $this->app->view->composer(['shop._attribute_filter', 'admin.products.form.attributes'], function ($view) {
            $view->with('product_attributes', \App\ProductAttribute::with('attribute_properties')->get());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
