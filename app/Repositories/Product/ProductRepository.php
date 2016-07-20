<?php

namespace App\Repositories\Product;

use App\Term;
use App\Services\ProductAttributeFilter;

interface ProductRepository
{
    /**
     * @param int   $id
     * @param array $with
     *
     * @return mixed
     */
    public function fetch($id, $with = []);

    public function all($with = []);

    /**
     * @param array $with
     *
     * @return mixed
     */
    public function getPaginated($with = []);

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function create($attributes);

    public function inCategory(Term $product_category);

    /**
     * Get a count of all models in the database.
     *
     * @return int
     */
    public function count();

    /**
     * Get a count of all low in stock but not out.
     *
     * @return int
     */
    public function countLowStock();

    /**
     * Get a count of all out-of-stock products.
     *
     * @return int
     */
    public function countOutOfStock();

    public function shopProducts(Term $productCategory);
}
