<?php

namespace App;

use Carbon\Carbon;
use App\Presenters\PresentableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Event extends Model implements HasMediaConversions
{
  use SoftDeletes, HasMediaTrait, PresentableTrait;

  protected $presenter = 'App\Presenters\EventPresenter';

  /**
   * The "booting" method of the model.
   *
   * @return void
   */
  protected static function boot()
  {
    parent::boot();

    /**
     * Set a slug on the event if it's not passed in.
     */
    static::saving(function ($event) {
      if (!$event->slug) {
        $event->slug = str_slug($event->title);
      }
      $event->adjustDates();
    });

    /**
     * Load the event venues and order by start date.
     */
    static::addGlobalScope('orderByStart', function (Builder $query) {
      $query->orderBy('start_date', 'ASC');
    });

    static::addGlobalScope('attachRelations', function (Builder $query) {
      $query->with(['venue', 'media']);
    });
  }

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->adjustDates();
  }

  /**
   * Mass-assignable fields.
   *
   * @var array
   */
  protected $fillable = [
    'title',
    'slug',
    'description',
    'website',
    'start_date',
    'end_date',
    'all_day',
    'address_id',
    'organiser',
  ];

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at', 'deleted_at'];

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

    $this->addMediaConversion('banner')
      ->setManipulations(['w' => 1300, 'h' => 1300])
      ->performOnCollections('images');
  }

  /**
   * Ensure the start date is parsed to a Carbon instance when being set.
   *
   * @param string|\DateTime $date
   */
  public function setStartDateAttribute($date)
  {
    $this->attributes['start_date'] = is_string($date) ? new Carbon($date) : $date;
  }

  /**
   * Ensure the end date is parsed to a Carbon instance when being set.
   *
   * @param string|\DateTime $date
   */
  public function setEndDateAttribute($date)
  {
    $this->attributes['end_date'] = is_string($date) ? new Carbon($date) : $date;
  }

  /**
   * An event belongs to a venue (address).
   *
   * @return Illuminate\Database\Eloquent\Relations\Relation]
   */
  public function venue()
  {
    return $this->belongsTo(Address::class, 'address_id');
  }

  /**
   * If the event in question is all-day set the start and end times to
   * the beginning and end of the day respectively.
   *
   * @return Event
   */
  protected function adjustDates()
  {
    if ($this->all_day) {
      $this->start_date = $this->start_date->startOfDay();
      $this->end_date = $this->end_date->endOfDay();
    }

    return $this;
  }

  /**
   * Only include upcoming or in-progress events.
   *
   * @param Builder $query
   *
   * @return void
   */
  public function scopeUpcoming(Builder $query)
  {
    $query->where('end_date', '>=', Carbon::now());
  }

  /**
   * Limit to event that have started before the given date, or now.
   *
   * @param Builder     $query
   * @param Carbon|null $date
   *
   * @return void
   */
  public function scopeBefore(Builder $query, Carbon $date = null)
  {
    $date = $date ?: Carbon::now();
    $query
      ->where('start_date', '<', $date)
      ->orderBy('end_date', 'DESC')
      ->take(10);
  }

  /**
   * Get the event's description as html.
   *
   * @return string
   */
  public function getDescriptionHtml()
  {
    return \Markdown::convertToHtml($this->description);
  }

  public function isUnderway()
  {
    return Carbon::now()->between($this->start_date, $this->end_date);
  }

  public function hasEnded()
  {
    return $this->end_date->isPast();
  }

  public function isUpcoming()
  {
    return $this->start_date->isFuture();
  }

  public function getFeaturedImageAttribute()
  {
    return $this->media->count()
      ? $this->media()
        ->first()
        ->getUrl('banner')
      : '/img/events/placeholder.jpg';
  }
}
