<?php

namespace App\Repositories\Product;

use App\Term;

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

  /**
   * Get all the current locations of products.
   *
   * @return Collection
   */
  public function getLocations();

  public function search($query);
}
