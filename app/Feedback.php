<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
  public $table = 'feedbacks';

  public $fillable = ['user_id', 'message'];

  public $timestamps = false;
}
