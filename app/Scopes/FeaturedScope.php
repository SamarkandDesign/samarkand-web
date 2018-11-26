<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

/**
 * A scope to order items by if they're featured.
 */
class FeaturedScope implements Scope
{
  /**
   * Apply the scope to a given Eloquent query builder.
   *
   * @param \Illuminate\Database\Eloquent\Builder $builder
   * @param \Illuminate\Database\Eloquent\Model   $model
   *
   * @return void
   */
  public function apply(Builder $builder, Model $model)
  {
    return $builder->orderBy('featured', 'DESC');
  }
}
