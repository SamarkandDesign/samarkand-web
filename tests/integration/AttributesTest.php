<?php

namespace Integration;

use App\AttributeProperty;
use App\Product;
use App\ProductAttribute;
use TestCase;

class AttributesTest extends TestCase
{
    /** @test **/
    public function it_filters_products_by_an_attribute()
    {
        $this->withoutEvents();

        $attribute = factory(ProductAttribute::class)->create(['name' => 'Size', 'slug' => 'size']);
        $property = factory(AttributeProperty::class)->create(['name' => 'Huge', 'slug' => 'huge', 'product_attribute_id' => $attribute->id]);

        $product_1 = factory(Product::class)->create();
        $product_2 = factory(Product::class)->create();

        $product_1->addProperty($property);

        $this->visit('/shop?filter[size]=huge')
             ->see($product_1->name)
             ->dontSee($product_2->name);
    }
}
