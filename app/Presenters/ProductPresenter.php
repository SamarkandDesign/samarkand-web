<?php

namespace App\Presenters;

use Illuminate\Support\HtmlString;

class ProductPresenter extends ModelPresenter
{
  protected $modelRoute = 'products';

  /**
   * Get a delimited string of a product's categories.
   *
   * @param string $delimiter
   * @param bool   $links     Should the categories link
   *
   * @return HtmlString
   */
  public function categoryList($delimiter = ', ', $links = true)
  {
    $categoryNames = $links
      ? $this->model->product_categories->map(function ($category) {
        return sprintf(
          '<a href="%s" title="edit %s category">%s</a>',
          route('admin.categories.edit', $category),
          $category->term,
          $category->term
        );
      })
      : $this->model->product_categories->pluck('term');

    return new HtmlString($categoryNames->implode($delimiter));
  }

  /**
   * Get a string representing the product's price, taking into account any sale price.
   *
   * @return HtmlString
   */
  public function price()
  {
    if (!$this->model->sale_price->value()) {
      return new HtmlString(
        sprintf(
          '%s<span itemprop="price">%s</span>',
          $this->model->price->symbol(),
          $this->model->price->asMoney()
        )
      );
    }

    return new HtmlString(
      sprintf(
        '<del>%s</del> <ins>%s</ins>',
        $this->model->price,
        sprintf(
          '%s<span itemprop="price">%s</span>',
          $this->model->sale_price->symbol(),
          $this->model->sale_price->asMoney()
        )
      )
    );
  }

  /**
   * Get an html img tag for the product's thumbnail.
   *
   * @param int $w The desired width of the thumbnail
   * @param int $h The desired height of the thumbnail
   *
   * @return HtmlString
   */
  public function thumbnail($w = 300, $h = null)
  {
    $h = $h ?: $w;

    return new HtmlString(
      sprintf(
        '<img src="%s" alt="%s" width="%s" height="%s" class="img-responsive">',
        $this->thumbnail_url($w, $h),
        htmlentities($this->model->name),
        $w,
        $h
      )
    );
  }

  /**
   * Get the URL of the product's thumbnail, or a placeholder if one doesn't exist.
   *
   * @param int $w The desired width of the thumbnail
   * @param int $h The desired height of the thumbnail
   *
   * @return string
   */
  public function thumbnail_url()
  {
    return $this->model->thumbnail ?: '/img/placeholder-square.png';
  }

  /**
   * Get a string representing the stock of the product.
   *
   * @return string
   */
  public function stock()
  {
    $stock = $this->model->stock_qty;
    if (is_null($stock)) {
      // Stock is not set, don't display
      return '';
    }

    return $stock > 0 ? sprintf('%s in stock', $stock) : 'Out of stock';
  }
}
