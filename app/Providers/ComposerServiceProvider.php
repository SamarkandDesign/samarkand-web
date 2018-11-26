<?php

namespace App\Providers;

use App\Product;
use App\MenuItem;
use App\Search\TokenGenerator;
use App\Countries\CountryRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Term\TermRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;

class ComposerServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
    $this->shareMenuItems();

    $this->shareRoles();

    $this->sharePostData();

    $this->shareProductIndexSettings();

    $this->shareProductMeta();

    $this->shareOrderCountWithSidebar();

    $this->shareAttributes();

    $this->shareProductCategories();

    $this->shareSearchKey();
  }

  private function shareMenuItems()
  {
    $this->app->view->composer(['partials._navbar', 'partials._footer'], function ($view) {
      $menuItems = $this->app->cache->rememberForever('app.menus', function () {
        return MenuItem::all()->groupBy('menu');
      });
      $view->with(compact('menuItems'));
    });
  }

  /**
   * Build up a special search key to be used just for search products on the front end.
   *
   * @return void [description]
   */
  private function shareSearchKey()
  {
    $tokenGenerator = $this->app->make(TokenGenerator::class);

    $this->app->view->composer('shop._product_search', function ($view) use ($tokenGenerator) {
      $view->with([
        'searchKey' => $tokenGenerator->getProductSearchToken(),
        'searchIndex' => (new Product())->searchableAs(),
      ]);
    });

    $this->app->view->composer('admin.products.index', function ($view) use ($tokenGenerator) {
      $view->with([
        'searchKey' => $tokenGenerator->getAdminProductToken(),
        'searchIndex' => (new Product())->searchableAs(),
      ]);
    });
  }

  private function shareProductMeta()
  {
    $this->app->view->composer('admin.products.form', function ($view) {
      $products = $this->app->make(ProductRepository::class);
      $view->with(['productLocations' => $products->getLocations()]);
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
      $roles = \App\Role::pluck('display_name', 'id');

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
    $this->app->view->composer(
      'admin.posts.index',
      \App\Composers\Admin\PostViewComposer::class . '@postCount'
    );
  }

  private function shareCountries()
  {
    $this->app->view->composer(['shop.checkout', 'addresses.form'], function ($view) {
      $countries_repository = $this->app->make(CountryRepository::class);

      $view->with('countries', $countries_repository->pluck());
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

    $this->app->view->composer('admin.products.index', function ($view) {
      $view->with('trashedCount', Product::onlyTrashed()->count());
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
    $this->app->view->composer(
      ['shop._attribute_filter', 'admin.products.form.attributes'],
      function ($view) {
        $view->with(
          'product_attributes',
          \App\ProductAttribute::with([
            'attribute_properties' => function ($query) {
              $query->orderBy('order', 'ASC');
            },
          ])->get()
        );
      }
    );
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
