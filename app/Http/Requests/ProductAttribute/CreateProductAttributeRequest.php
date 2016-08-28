<?php

namespace App\Http\Requests\ProductAttribute;

use App\Http\Requests\Request;

class CreateProductAttributeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $slug = $this->get('slug', str_slug($this->get('name')));

        return [
            'name'          => 'required|unique:product_attributes,name',
            'slug'          => 'alpha_dash|unique:product_attributes,slug',
            'order'         => 'integer',
        ];
    }
}
