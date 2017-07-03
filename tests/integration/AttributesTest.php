<?php

namespace Integration;

use App\AttributeProperty;
use App\Product;
use App\ProductAttribute;
use TestCase;

class AttributesTest extends TestCase
{
    use \FlushesProductEvents;

    /** @test **/
    public function it_filters_products_by_an_attribute()
    {
        $this->withoutEvents();

        $attribute = factory(ProductAttribute::class)->create(['name' => 'Size', 'slug' => 'size']);
        $property = factory(AttributeProperty::class)->create(['name' => 'Huge', 'slug' => 'huge', 'product_attribute_id' => $attribute->id]);

        $product_1 = factory(Product::class)->create();
        $product_2 = factory(Product::class)->create();

        $product_1->addProperty($property);

        $response = $this->get('/shop?filter[size]=huge');
        $this->assertContains($product_1->name, $response->getContent());
        $this->assertNotContains($product_2->name, $response->getContent());
    }
}
