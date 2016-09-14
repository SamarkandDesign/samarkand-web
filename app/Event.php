<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

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
            $query->with('venue')->orderBy('start_date', 'ASC');
        });
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->adjustDates();
    }

    protected $fillable = ['title', 'slug', 'description', 'website', 'start_date', 'end_date', 'all_day', 'address_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function setStartDateAttribute($date)
    {
        $this->attributes['start_date'] = is_string($date) ? new Carbon($date) : $date;
    }

    public function setEndDateAttribute($date)
    {
        $this->attributes['end_date'] = is_string($date) ? new Carbon($date) : $date;
    }

    public function venue()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    protected function adjustDates()
    {
        if ($this->all_day) {
            $this->start_date = $this->start_date->startOfDay();
            $this->end_date = $this->end_date->endOfDay();
        }
    }

    public function scopeUpcoming($query)
    {
        $query->where('start_date', '>=', Carbon::now());
    }

    public function eventStatus()
    {
        $now = new Carbon();

        if ($this->isUnderway()) {
            return 'Underway';
        }

        if ($this->hasEnded()) {
            return 'Ended ' . $this->end_date->diffForHumans();
        }

        if ($this->isUpcoming()) {
            return 'Starts ' . $this->start_date->diffForHumans();
        }
    }

    public function isUnderway()
    {
        return (new Carbon())->between($this->start_date, $this->end_date);
    }

    public function hasEnded()
    {
        return $this->end_date->isPast();
    }

    public function isUpcoming()
    {
        return $this->start_date->isFuture();
    }

    public function duration()
    {
        $days = $this->start_date->diffInDays($this->end_date) + 1;
        return  sprintf('%s %s', $days, str_plural('day', $days));
    }
}
