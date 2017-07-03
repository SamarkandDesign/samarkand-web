<?php

namespace App\Http\Controllers\Api;

use App\AttributeProperty;
use App\ProductAttribute;

class AttributePropertiesControllerTest extends \TestCase
{
    /** @test */
  public function it_creates_an_attribute_property()
  {
      $product_attribute = factory(ProductAttribute::class)->create();

      $response = $this->post(route('api.product_attributes.attribute_properties.store', $product_attribute->id), [
      'name' => 'Foo',
    ]);

    $response->assertJson([
      'name' => 'Foo',
      'slug' => 'foo',
    ]);

      $this->assertDatabaseHas('attribute_properties', [
      'slug'                 => 'foo',
      'product_attribute_id' => $product_attribute->id,
    ]);
  }

  /** @test */
  public function it_updates_an_attribute_property()
  {
      $attribute_property = factory(AttributeProperty::class)->create();

      $response = $this->patch(route('api.attribute_properties.update', $attribute_property), [
      'name' => 'Foo',
      'slug' => 'foo',
    ]);
    $response->assertJson([
      'name' => 'Foo',
      'slug' => 'foo',
    ]);

      $this->assertDatabaseHas('attribute_properties', [
      'slug' => 'foo',
    ]);
  }

  /** @test */
  public function it_deletes_an_attribute_property()
  {
      $attribute_property = factory(AttributeProperty::class)->create();
      $response = $this->delete(route('api.attribute_properties.delete', $attribute_property));

      $this->assertDatabaseMissing('attribute_properties', [
      'slug'                 => $attribute_property->slug,
      'product_attribute_id' => $attribute_property->product_attribute_id,
    ]);
  }
}
