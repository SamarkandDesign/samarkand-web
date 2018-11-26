<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ProductAttributeFilter
{
  private $filters;

  private $builder;

  public function __construct(Request $request)
  {
    $this->filters = collect($request->get('filter', []));
  }

  public function getFilterHash()
  {
    return md5($this->filters->toJson());
  }

  /**
   * Apply the attribute filter to the builder.
   *
   * @param Builder $builder
   *
   * @return Builder
   */
  public function apply(Builder $builder)
  {
    $this->builder = $builder;

    foreach ($this->filters as $attribute => $property) {
      $this->builder->whereHas('attribute_properties', function ($query) use (
        $attribute,
        $property
      ) {
        $query
          ->where('slug', $property)
          ->whereHas('product_attribute', function ($query) use ($attribute) {
            $query->where('slug', $attribute);
          });
      });
    }

    return $this->builder;
  }
}
