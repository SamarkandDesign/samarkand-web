<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
  use SoftDeletes;

  /**
   * The table used by the model.
   *
   * @var string
   */
  protected $table = 'addresses';

  /**
   * The fields that are mass-assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'phone',
    'line_1',
    'line_2',
    'line_3',
    'city',
    'country',
    'postcode',
    'lat',
    'lng',
  ];

  /**
   * Mutate these dates to Carbon instances.
   *
   * @var array
   */
  protected $dates = ['deleted_at'];

  /**
   * Validation rules for an address.
   *
   * @var array
   */
  public static $rules = [
    'name' => 'max:255',
    'line_1' => 'required|max:255',
    'city' => 'required|max:255',
    'postcode' => 'required|max:255',
    'country' => 'required|size:2',
    'phone' => 'max:24',
  ];

  public function addressable()
  {
    return $this->morphTo();
  }

  /**
   * Provide a user ID to enable determining the owner if owned by a user.
   *
   * @return int|null
   */
  public function getUserIdAttribute()
  {
    return $this->addressable_type === User::class ? $this->addressable_id : null;
  }

  public function getFullNameAttribute()
  {
    return $this->name;
  }

  /**
   * Get the full country name.
   *
   * @param string $code The address's country code
   *
   * @return string The full country name
   */
  public function getCountryNameAttribute()
  {
    $countries = \App::make(\App\Countries\CountryRepository::class);

    return $countries->getByCode($this->country);
  }

  /**
   * Get the raw country code.
   *
   * @return string
   */
  public function getCountryCodeAttribute()
  {
    return $this['attributes']['country'];
  }

  public function toOneLineString()
  {
    $str = $this->line_1;
    if ($this->line_2) {
      $str .= ", {$this->line_2}";
    }
    if ($this->line_3) {
      $str .= ", {$this->line_3}";
    }

    return $str . ", {$this->city}, {$this->postcode}, {$this->country}";
  }
}
