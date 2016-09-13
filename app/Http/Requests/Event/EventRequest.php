<?php

namespace App\Http\Requests\Event;

use App\Http\Requests\Request;
use Carbon\Carbon;

class EventRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // We'll allow users to edit each other's posts for now
        // Maybe add some role-based authorization at some point
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function defaultRules()
    {
        return [
        'title'            => 'required|max:255',
        'website'           => 'url|max:255',
        'all_day'           => 'boolean',
        'start_date'     => 'required|date',
        'end_date'     => 'required|date',
        'address_id'          => 'integer',
        'create_new_venue' => 'boolean',
        ];
    }
}
