<?php

namespace App;

use TestCase;
use App\AttributeProperty;

class AttributeTest extends TestCase
{
  /** @test **/
  public function it_gets_a_count_of_related_products()
  {
    $this->withoutEvents();

    $property = factory(AttributeProperty::class)->create();

    $products = factory(Product::class, 3)->create();

    $products->each(function ($product) use ($property) {
      $product->addProperty($property);
    });

    $this->assertEquals(3, $property->products->count());
  }

  /** @test */
  public function it_gets_its_child_properties()
  {
    $attribute = factory(ProductAttribute::class)->create(['name' => 'Size']);
    $properties = factory(AttributeProperty::class, 3)->create(['product_attribute_id' => $attribute->id]);

    $this->assertCount(3, $attribute->attribute_properties);
  }
}
