<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MenuItem extends \Baum\Node
{
  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('orderByOrder', function (Builder $query) {
        $query->orderBy('order');
    });
  }

  protected $table = 'menu_items';

  public $timestamps = false;

  protected $fillable = ['menu', 'label', 'link', 'order'];
}
