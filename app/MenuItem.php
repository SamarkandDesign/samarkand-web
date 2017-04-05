<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderByOrder', function (Builder $query) {
            $query->orderBy('menu', 'ASC')->orderBy('order', 'ASC');
        });
    }

    protected $table = 'menu_items';

    public $timestamps = false;

    protected $fillable = ['menu', 'label', 'link', 'order'];
}
