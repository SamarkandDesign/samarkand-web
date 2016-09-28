<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderNote extends Model
{
    protected $fillable = ['order_id', 'body', 'key', 'user_id'];

    protected $dates = ['created_at'];

    public $timestamps = false;

    public static function boot()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIconAttribute()
    {
        $icons = [
            'payment_completed' => 'credit-card',
            'payment_failed'    => 'times',
            'status_changed'    => 'flag',
        ];

        return array_get($icons, $this->key, 'envelope');
    }
}
