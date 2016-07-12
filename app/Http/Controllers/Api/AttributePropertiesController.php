<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductAttribute\CreateProductAttributeRequest;
use App\Http\Requests\AttributeProperty\CreateAttributePropertyRequest;
use App\Http\Requests\AttributeProperty\UpdateAttributePropertyRequest;
use App\AttributeProperty;
use App\ProductAttribute;
use Illuminate\Http\Request;

class AttributePropertiesController extends Controller
{
    /**
     * Get all properties for a given attribute.
     *
     * @param string $slug The slug of the attribute
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductAttribute $product_attribute = null)
    {
        if ($product_attribute) {
            return $product_attribute->attribute_properties;
        }

        return AttributeProperty::all();
    }

    public function store(CreateAttributePropertyRequest $request, ProductAttribute $product_attribute)
    {
      $attribute_property = new AttributeProperty($request->all());

      return $product_attribute->attribute_properties()->save($attribute_property);
    }

    public function update(UpdateAttributePropertyRequest $request, AttributeProperty $attribute_property)
    {
      $attribute_property->update($request->all());

      return $attribute_property;
    }

    public function destroy(AttributeProperty $attribute_property)
    {
      $attribute_property->delete();
      return 'success';
    }
}
