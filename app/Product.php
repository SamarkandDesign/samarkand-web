<?php

namespace App;

use Carbon\Carbon;
use App\Values\Price;
use App\Traits\Postable;
use App\Contracts\Termable;
use Laravel\Scout\Searchable;
use App\Presenters\PresentableTrait;
use Illuminate\Database\Eloquent\Model;
use App\Services\ProductAttributeFilter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Product extends Model implements HasMediaConversions, Termable
{
  use PresentableTrait, HasMediaTrait, SoftDeletes, Postable, Searchable;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  public $table = 'products';

  /**
   * Get the URL to a single product page.
   *
   * @return string
   */
  public function getUrlAttribute()
  {
    return sprintf('/shop/%s/%s', $this->product_category->slug, $this->slug);
  }

  /**
   * Set the image sizes for product attachments.
   *
   * @return void
   */
  public function registerMediaConversions()
  {
    $this->addMediaConversion('thumb')
      ->setManipulations(['w' => 500, 'h' => 500, 'fit' => 'crop'])
      ->performOnCollections('images');

    $this->addMediaConversion('wide')
      ->setManipulations(['w' => 1300, 'h' => 900, 'fit' => 'crop'])
      ->performOnCollections('images');
  }

  /**
   * Set the polymorphic relation.
   *
   * @return mixed
   */
  public function media()
  {
    return $this->morphMany(config('laravel-medialibrary.media_model'), 'model')->orderBy(
      'order_column',
      'ASC'
    );
  }

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['created_at', 'updated_at', 'published_at', 'deleted_at'];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'slug',
    'description',
    'user_id',
    'media_id',
    'status',
    'price',
    'sale_price',
    'sku',
    'stock_qty',
    'published_at',
    'listed',
    'location',
    'featured',
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'listed' => 'boolean',
    'featured' => 'boolean',
    'stock_qty' => 'integer',
  ];

  protected $presenter = 'App\Presenters\ProductPresenter';

  /**
   * Get all the terms for a product
   * Restrict only to normal taxonomies.
   *
   * @return \Illuminate\Database\Eloquent\Relations\Relation
   */
  public function terms()
  {
    return $this->morphToMany(Term::class, 'termable');
  }

  /**
   * Get all the attributes for a product.
   *
   * @return \Illuminate\Database\Eloquent\Relations\Relation
   */
  public function attribute_properties()
  {
    return $this->belongsToMany(AttributeProperty::class);
  }

  /**
   * Add an attribute to a product.
   *
   * @param Term $attribute
   */
  public function addProperty(AttributeProperty $property)
  {
    $this->attribute_properties()->save($property);

    return $this;
  }

  /**
   * A product belongs to many product categories.
   *
   * @return \Illuminate\Database\Eloquent\Relations\Relation
   */
  public function product_categories()
  {
    return $this->morphToMany(Term::class, 'termable')->where('taxonomy', 'product_category');
  }

  /**
   * Ensure an uncategorised term exists and assign it to the product,
   * removing it from all other categories.
   *
   * @return Product
   */
  public function makeUncategorised()
  {
    $term = Term::firstOrCreate([
      'taxonomy' => 'product_category',
      'slug' => 'uncategorised',
      'term' => 'Uncategorised',
    ]);

    return $this->syncTerms([$term->id]);
  }

  /**
   * Sync terms to a product.
   *
   * @param \Illuminate\Database\Eloquent\Collection|array $terms
   *
   * @return Product
   */
  public function syncTerms($terms = [])
  {
    if (!count($terms)) {
      return $this->makeUncategorised();
    }

    if ($terms instanceof \Illuminate\Database\Eloquent\Collection) {
      $terms = $terms->pluck('id')->toArray();
    }
    $this->product_categories()->sync($terms);

    // $this->product_categories()->detach($this->product_categories->pluck('id'));
    // $this->product_categories()->attach($terms);

    return $this;
  }

  /**
   * Sync attributes to a product.
   *
   * @param \Illuminate\Database\Eloquent\Collection|array $terms
   *
   * @return Product
   */
  public function syncAttributes($attributes = [])
  {
    if ($attributes instanceof \Illuminate\Database\Eloquent\Collection) {
      $attributes = $attributes->pluck('id')->toArray();
    }

    $this->attribute_properties()->sync($attributes);

    return $this;
  }

  /**
   * Apply the attribute filter scope.
   *
   * @param Builder                $query
   * @param ProductAttributeFilter $filter
   *
   * @return Builder
   */
  public function scopeFilter($query, ProductAttributeFilter $filter)
  {
    return $filter->apply($query);
  }

  /**
   * Limit the query to only sale items.
   *
   * @param Builder $query
   *
   * @return Builder
   */
  public function scopeOnSale($query)
  {
    return $query->where('sale_price', '>', 0);
  }

  /**
   * Only get listed products.
   *
   * @param Builder $query
   *
   * @return Builder
   */
  public function scopeListed($query)
  {
    return $query->where('listed', true);
  }

  /**
   * Limit the query to only low stocked items. Excludes out-of-stock items.
   *
   * @param Builder $query
   *
   * @return Builder
   */
  public function scopeLowStock($query)
  {
    return $query
      ->where('stock_qty', '>', 0)
      ->where('stock_qty', '<=', config('shop.low_stock_qty'));
  }

  /**
   * Limit the query to only out-of-stock items.
   *
   * @param Builder $query
   *
   * @return Builder
   */
  public function scopeOutOfStock($query)
  {
    return $query->where('stock_qty', '<=', 0);
  }

  /**
   * Limit the query to only in-stock items.
   *
   * @param Builder $query
   *
   * @return Builder
   */
  public function scopeInStock($query)
  {
    return $query->where('stock_qty', '>', 0);
  }

  /**
   * Parses a date string into a Carbon instance for saving.
   *
   * This shouldn't really need to be done, but Laravel's automatic date
   * mutators expects strings to be in the format Y-m-d H-i-s which is
   * not always the case; such as for 'datetime-local' html5 fields.
   *
   * @param mixed $date The date to be parsed
   */
  public function setPublishedAtAttribute($date)
  {
    if (is_string($date)) {
      $this->attributes['published_at'] = new Carbon($date);
    }
  }

  /**
   * Cast the stock qty to null if it's an empty string.
   *
   * @param mixed $qty
   */
  public function setStockQtyAttribute($qty)
  {
    $this->attributes['stock_qty'] = $qty === '' ? null : $qty;
  }

  /**
   * Get the URL of the product's thumbnail.
   *
   * @return string
   */
  public function getThumbnailAttribute()
  {
    // TODO: fix when medialibrary is updated
    // return '/img/placeholder-square.png';
    return $this->media->count() ? $this->media->first()->thumbnail_url : '';
  }

  /**
   * Cast the product's price to an integer for storage.
   *
   * @param float $price
   */
  public function setPriceAttribute($price)
  {
    $this->attributes['price'] = intval(100 * $price);
  }

  /**
   * Cast the product's sale price to an integer for storage.
   *
   * @param float $price
   */
  public function setSalePriceAttribute($price)
  {
    if ($price) {
      $this->attributes['sale_price'] = intval(100 * $price);
    } else {
      $this->attributes['sale_price'] = null;
    }
  }

  /**
   * Cast the product's price to a float.
   *
   * @param int $price
   *
   * @return Price
   */
  public function getPriceAttribute($price)
  {
    return new Price($price);
  }

  /**
   * Cast the product's sale price to a float.
   *
   * @param int $price
   *
   * @return Price
   */
  public function getSalePriceAttribute($price)
  {
    return new Price($price);
  }

  /**
   * Get the product's description as html.
   *
   * @return string
   */
  public function getDescriptionHtml()
  {
    return \Markdown::convertToHtml($this->description);
  }

  /**
   * The field to use to display the parent name.
   *
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Get the product's product category.
   *
   * @return \App\Term
   */
  public function getProductCategoryAttribute()
  {
    return $this->productCategory();
  }

  /**
   * Get the product's product category.
   * Gets the first if more than one set.
   * Sets it to uncategorised if none set.
   *
   * @return \App\Term
   */
  public function productCategory()
  {
    if ($this->product_categories->count() === 0) {
      $this->makeUncategorised();

      return $this->fresh()->product_categories->first();
    }

    return $this->product_categories->first();
  }

  /**
   * Get the price of the product.
   *
   * @return Price
   */
  public function getPrice()
  {
    $value = $this->sale_price->value() > 0 ? $this->sale_price->value() : $this->price->value();

    return new Price($value);
  }

  /**
   * Get whether a product is in stock.
   *
   * @return bool
   */
  public function inStock()
  {
    return $this->stock_qty > 0;
  }

  /** SEARCH **/

  /**
   * Returns an array with properties which must be indexed.
   *
   * @return array
   */
  public function toSearchableArray()
  {
    $array = $this->toArray();
    if (array_key_exists('product_categories', $array)) {
      unset($array['product_categories']);
    }

    return array_merge($array, [
      'categories' => $this->product_categories->pluck('term'),
      'properties' => $this->attribute_properties->pluck('name'),
      'thumbnail' => $this->present()->thumbnail_url,
      'url' => $this->url,
      'price' => $this->price->asDecimal(),
      'sale_price' => $this->sale_price->asDecimal(),
    ]);
  }

  /**
   * Get the index name for the model.
   *
   * @return string
   */
  public function searchableAs()
  {
    return $this->getTable() . '_' . \App::environment();
  }

  /**
   * Return the id of the searchable subject.
   *
   * @return string
   */
  public function getSearchableId()
  {
    return $this->id;
  }
}
