<?php

namespace App\Repositories\Product;

use App\Product;
use App\Repositories\DbRepository;
use App\Services\ProductAttributeFilter;
use App\Term;

class DbProductRepository extends DbRepository implements ProductRepository
{
    private $filter;

    /**
     * @param Product $product
     */
    public function __construct(Product $product, ProductAttributeFilter $filter)
    {
        $this->model = $product;
        $this->filter = $filter;
    }

    /**
     * @param array $attributes
     *
     * @return static
     */
    public function create($attributes)
    {
        $product = $this->model->create($attributes);
        if (isset($attributes['terms'])) {
            $product->terms()->sync($attributes['terms']);
        }

        return $product;
    }

    public function inCategory(Term $product_category)
    {
        return $product_category->products()->paginate(config('shop.products_per_page'));
    }

    /**
     * Build a query for all instances of a model.
     *
     *
     * @param array $with
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function queryAll($with = [])
    {
        return $this->model->filter($this->filter)
                           ->with($with)
                           ->latest();
    }

    /**
     * Get a count of all low in stock but not out.
     *
     * @return int
     */
    public function countLowStock()
    {
        return $this->model->lowStock()->count();
    }

    /**
     * Get a count of all out-of-stock products.
     *
     * @return int
     */
    public function countOutOfStock()
    {
        return $this->model->outOfStock()->count();
    }

    public function shopProducts(Term $productCategory)
    {
        if (!$productCategory->slug) {
            $query = $this->model->with('media');
        } else {
            $query = $productCategory->products()->with('media');
        }

        if (!config('shop.show_out_of_stock')) {
            $query = $query->inStock();
        }

        return $query->listed()
                     ->with('product_categories')
                     ->filter($this->filter)
                     ->orderBy('featured', 'DESC')
                     ->paginate(config('shop.products_per_page'));
    }

    /**
     * Get all the current locations of products
     * @return Collection
     */
    public function getLocations()
    {
        return Product::groupBy('location')->pluck('location');
    }
}
