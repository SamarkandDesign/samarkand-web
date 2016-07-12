<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeProperty extends Model
{
  /**
   * The "booting" method of the model.
   *
   * @return void
   */
  protected static function boot()
  {
      parent::boot();

      /**
       * Set a slug on the attribute if it's not passed in.
       */
      static::creating(function ($attribute) {
          if (!$attribute->slug) {
              $attribute->slug = str_slug($attribute->name);
          }
      });
  }

    protected $table = 'attribute_properties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'order'];

    public function product_attribute()
    {
      return $this->belongsTo(ProductAttribute::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
