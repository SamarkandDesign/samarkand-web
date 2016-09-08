<?php

namespace App\Http\Requests\Page;

// use App\Http\Requests\Request;

class UpdatePageRequest extends PageRequest
{
    public function forbiddenResponse()
    {
        return $this->redirector->back()
        ->with('alert-class', 'danger')
        ->with('alert', 'You are not allowed to edit this page');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $page_id = $this->route('page')->id;
        $child_pages = $this->route('page')->descendantsAndSelf()->pluck('id');

        // Need to ensure we don't move the page into a child page of itself
        return [
            'title'           => 'max:255',
            'slug'            => 'alpha_dash|max:255|unique:pages,slug,'.$page_id,
            'meta_description' => 'max:255',
            'published_at'    => 'date',
            'status'          => 'alpha_dash',
            'parent_id'       => 'numeric|exists:pages,id|not_in:'.$child_pages->implode(','),
        ];
    }
}
