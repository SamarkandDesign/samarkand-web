<?php

namespace App\Http\Requests\Event;

class CreateEventRequest extends EventRequest
{
  public function rules()
  {
    return array_merge($this->defaultRules(), [
      'slug' => 'max:255|alpha_dash|unique:events,slug',
    ]);
  }
}
