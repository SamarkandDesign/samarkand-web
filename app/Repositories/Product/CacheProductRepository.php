<?php

namespace App\Repositories\Product;

use App\Term;
use App\Product;
use Illuminate\Http\Request;
use App\Repositories\CacheRepository;

class CacheProductRepository extends CacheRepository implements ProductRepository
{
  /**
   * @param ProductRepository $repository
   * @param Product           $model
   */
  public function __construct(ProductRepository $repository, Product $model = null)
  {
    $this->repository = $repository;
    $this->model = $model ?: new Product();

    $this->tag = $this->model->getTable();

    $this->setModifier();
  }

  public function inCategory(Term $product_category)
  {
    $tags = array_merge([$this->tag], ['terms']);
    $tag = "{$this->tag}.inCategory.{$product_category->slug}";

    try {
      return \Cache::tags($tags)->remember($tag, config('cache.time'), function () use (
        $product_category
      ) {
        return $this->repository->inCategory($product_category);
      });
    } catch (\Exception $e) {
      \Log::warning('Error fetching cached resource', ['error' => $e, 'tag' => $tag]);
      return $this->repository->inCategory($product_category);
    }
  }

  protected function setModifier()
  {
    $request = app(Request::class);

    if (count(($params = $request->all()))) {
      $this->modifier .= '.' . md5(json_encode($params));
    }
  }

  /**
   * Get a count of all low in stock but not out.
   *
   * @return int
   */
  public function countLowStock()
  {
    return \Cache::tags($this->tag)->remember(
      'lowStockedProducts',
      config('cache.time'),
      function () {
        return $this->repository->countLowStock();
      }
    );
  }

  /**
   * Get a count of all out-of-stock products.
   *
   * @return int
   */
  public function countOutOfStock()
  {
    return \Cache::tags($this->tag)->remember(
      'outOfStockProducts',
      config('cache.time'),
      function () {
        return $this->repository->countOutOfStock();
      }
    );
  }

  public function shopProducts(Term $productCategory)
  {
    $cacheString =
      "shopProducts.{$this->modifier}" . (!$productCategory->slug ? '' : $productCategory->slug);

    try {
      return \Cache::tags($this->tag)->remember(
        $cacheString,
        config('cache.time'),
        function () use ($productCategory) {
          return $this->repository->shopProducts($productCategory);
        }
      );
    } catch (\Exception $e) {
      \Log::warning('Error fetching cached resource', ['error' => $e, 'tag' => $cacheString]);
      return $this->repository->shopProducts($productCategory);
    }
  }

  /**
   * Get all the current locations of products.
   *
   * @return Collection
   */
  public function getLocations()
  {
    return \Cache::tags($this->tag)->remember(
      'productLocations',
      config('cache.time'),
      function () {
        return $this->repository->getLocations();
      }
    );
  }

  public function search($query)
  {
    return $this->repository->search($query);
  }
}
