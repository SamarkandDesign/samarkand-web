<?php

namespace App\Http\Requests\ProductAttribute;

use App\Http\Requests\Request;

class UpdateProductAttributeRequest extends Request
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
    $attribute = $this->route('product_attribute');
    $name = $this->get('name');
    $slug = $this->get('slug', str_slug($name));

    return [
      'name' => 'required|unique:product_attributes,name,' . $attribute->id . ',id,slug,' . $name, // property is only unique for a given attribute
      'slug' => 'required|unique:product_attributes,slug,' . $attribute->id . ',id,slug,' . $slug, // property slug is only unique for a given attribute
      'order' => 'integer',
    ];
  }
}
