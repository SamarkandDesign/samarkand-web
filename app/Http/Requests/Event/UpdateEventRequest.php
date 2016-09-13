<?php

namespace App\Http\Requests\Event;

class UpdateEventRequest extends EventRequest
{
	public function forbiddenResponse()
	{
		return $this->redirector->back()
		->with('alert-class', 'danger')
		->with('alert', 'You are not allowed to edit this event');
	}

	public function rules()
	{
		return array_merge($this->defaultRules(), [
			'slug'             => 'max:255|alpha_dash|unique:events,slug,'.$this->route('event')->id,
			]);
	}
}
