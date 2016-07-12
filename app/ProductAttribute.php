<?php

namespace App;

use App\Scopes\SortTermScope;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope(new SortTermScope());

        /**
         * Set a slug on the attribute if it's not passed in.
         */
        static::creating(function ($attribute) {
            if (!$attribute->slug) {
                $attribute->slug = str_slug($attribute->name);
            }
        });
    }

    protected $table = 'product_attributes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];

    public function attribute_properties()
    {
      return $this->hasMany(AttributeProperty::class);
    }
}
