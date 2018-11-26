<?php

namespace App\Http\Requests\AttributeProperty;

use App\Http\Requests\Request;

class UpdateAttributePropertyRequest extends Request
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
    $attribute_property = $this->route('attribute_property');

    return [
      'name' =>
        "unique:attribute_properties,name,{$attribute_property->id},id,product_attribute_id," .
          $attribute_property->product_attribute_id, // name is only unique for a given attribute
      'slug' =>
        "alpha_dash|unique:attribute_properties,slug,{$attribute_property->id},id,product_attribute_id," .
          $attribute_property->product_attribute_id,
      'order' => 'integer',
    ];
  }
}
