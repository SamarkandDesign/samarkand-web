<?php

namespace App;

use App\Scopes\SortTermScope;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
  /**
   * The "booting" method of the model.
   *
   * @return void
   */
  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope(new SortTermScope());

    /**
     * Set a slug on the term if it's not passed in.
     */
    static::creating(function ($term) {
      if (!$term->slug) {
        $term->slug = str_slug($term->term);
      }
    });
  }

  /**
   * The database table used by the model.
   *
   * @var string
   */
  public $table = 'terms';

  /**
   * All of the relationships to be touched.
   *
   * @var array
   */
  protected $touches = ['products'];

  /**
   * The taxonomies in use.
   *
   * @var array
   */
  public static $taxonomies = [
    'category' => 'Categories',
    'tag' => 'Tags',
    'product_category' => 'Product Categories',
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['taxonomy', 'term', 'slug', 'order'];

  public function posts()
  {
    return $this->morphedByMany('App\Post', 'termable');
  }

  public function products()
  {
    return $this->morphedByMany('App\Product', 'termable', null, 'term_id');
  }

  /**
   * If a term isn't set, get the unslugged version of the slug.
   *
   * @param string $term
   *
   * @return string
   */
  public function getTermAttribute($term)
  {
    if (!$term) {
      return ucwords(\Present::unslug($this->slug));
    }

    return $term;
  }

  public function setSlugAttribute($slug)
  {
    $this->attributes['slug'] = str_slug($slug);
  }

  /**
   * Get a presentable version of the taxonomy.
   *
   * @return string
   */
  public function getTaxonomy()
  {
    return ucwords(\Present::unslug($this->taxonomy));
  }
}
