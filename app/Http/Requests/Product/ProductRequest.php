<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\Request;

class ProductRequest extends Request
{
  /**
   * Determine if the user is authorized to make this request.
   * Admin product routes are protected by admin middleware so no need to authorise here
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
  public function defaultRules()
  {
    $price = $this->get('price', 9999999);

    return [
      'name' => 'required|string',
      'slug' => 'required|alpha_dash|unique:products,slug',
      'sku' => 'required|unique:products,sku',
      'price' => 'filled|numeric|min:0',
      'sale_price' => sprintf('filled|numeric|min:0|max:%f', $price),
      'stock_qty' => 'filled|integer',
      'user_id' => 'required|integer',
    ];
  }

  public function messages()
  {
    return [
      'sale_price.max' => 'The sale price should be less than the normal price',
    ];
  }
}
