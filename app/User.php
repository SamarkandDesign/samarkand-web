<?php

namespace App;

use App\Traits\RoleableTrait;
use App\Presenters\PresentableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use PresentableTrait, RoleableTrait, Notifiable;

    public static function boot()
    {
        parent::boot();

        /**
         * Trigger an update that will set the extra attributes.
         */
        static::creating(function ($user) {
            $user->api_token = str_random(60);
        });
    }

    /**
     * The roles that should always be available.
     *
     * @var array
     */
    public static $base_roles = [
        'customer'      => 'Customer',
        'subscriber'    => 'Subscriber',
        'manager'       => 'Manager',
        'admin'         => 'Admin',
    ];

    /**
     * The presenter instance to use.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\UserPresenter';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The fields to be parsed into Carbon instance.
     *
     * @var array
     */
    protected $dates = ['last_seen_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'name', 'email', 'password', 'role_id', 'last_seen_at', 'telegram_id', 'is_shop_manager', 'trade_discount'];

    /**
     * Fields that can only be assigned if acting as an admin.
     *
     * @var array
     */
    public $adminFields = ['role_id', 'is_shop_manager', 'trade_discount'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * A user has several orders.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'DESC');
    }

    /**
     * A user has several addresses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Hash the password when setting it on a user.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Does the user own the model.
     *
     * @param Model $model The model to check
     *
     * @return bool
     */
    public function owns(Model $model)
    {
        return $this->id == $model->user_id;
    }

    /**
     * Whether a user has any orders.
     *
     * @return bool
     */
    public function hasOrders()
    {
        return $this->orders->count();
    }

    /**
     * Attach an address to a user.
     *
     * @param Address $address
     *
     * @return Address
     */
    public function addAddress(Address $address)
    {
        return $this->addresses()->save($address);
    }

    /**
     * Has the user been auto-created? I.e. they've never logged in.
     *
     * @return bool
     */
    public function autoCreated()
    {
        return is_null($this->last_seen_at);
    }

    /**
     * Limit users to just those assigned as shop admins.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeShopAdmins($query)
    {
        $query->where('is_shop_manager', true);
    }
}
