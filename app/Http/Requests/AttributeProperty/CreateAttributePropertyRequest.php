<?php

namespace App\Http\Requests\AttributeProperty;

use App\Http\Requests\Request;

class CreateAttributePropertyRequest extends Request
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
      $product_attribute = $this->route('product_attribute');

        return [
            'name'      => 'required|unique:attribute_properties,name,NULL,id,product_attribute_id,' . $product_attribute->id, // name is only unique for a given attribute
            'slug'      => 'alpha_dash|unique:attribute_properties,slug,NULL,id,product_attribute_id,' . $product_attribute->id,
            'order'     => 'integer',
        ];
    }
}
