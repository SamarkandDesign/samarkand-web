<?php

namespace App;

use Carbon\Carbon;
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
        static::creating(function ($event) {
            if (!$event->slug) {
                $event->slug = str_slug($event->title);
            }
        });
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

    public function scopeUpcoming($query)
    {
        $query->where('start_date', '>=', Carbon::now())->orderBy('start_date', 'ASC');
    }
}
