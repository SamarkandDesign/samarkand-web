<?php

namespace App;

use TestCase;

class ProductTest extends TestCase
{
  use \FlushesProductEvents;

  /** @test */
  public function it_gets_the_searchable_body_of_a_product()
  {
    $product = factory(Product::class)->create();
    $attribute = factory(ProductAttribute::class)->create();
    $properties = collect([
      factory(AttributeProperty::class)->create(['product_attribute_id' => $attribute->id]),
      factory(AttributeProperty::class)->create(['product_attribute_id' => $attribute->id]),
    ]);
    $product->attribute_properties()->saveMany($properties);

    $this->assertNotEmpty($product->toSearchableArray()['properties']);
  }

  /** @test **/
  public function it_gets_the_usable_price_of_a_product()
  {
    $product = factory(Product::class)->make([
      'price' => 20,
      'sale_price' => 0,
    ]);
    $this->assertEquals(20, $product->getPrice()->asDecimal());

    $product = factory(Product::class)->make([
      'price' => 20,
      'sale_price' => 15,
    ]);
    $this->assertEquals(15, $product->getPrice()->asDecimal());
  }

  /** @test **/
  public function it_gets_the_category_of_a_product()
  {
    $product = factory(Product::class)->create();

    $this->assertEquals('Uncategorised', $product->product_category->term);

    $product_category = factory(Term::class)->create([
      'taxonomy' => 'product_category',
    ]);

    $product->terms()->sync([$product_category->id]);
    $product = Product::find($product->id);

    $this->assertEquals($product_category->term, $product->product_category->term);
  }

  /** @test **/
  public function it_gets_the_description_as_html()
  {
    $product = factory(Product::class)->make([
      'description' => '# Hello World!',
    ]);

    $this->assertContains('<h1>Hello World!</h1>', $product->getDescriptionHtml());
  }

  /** @test **/
  public function it_gets_whether_the_product_is_in_stock()
  {
    $product = factory(Product::class)->make([
      'stock_qty' => 4,
    ]);

    $this->assertTrue($product->inStock());

    $product->stock_qty = 0;

    $this->assertFalse($product->inStock());
  }

  /** @test **/
  public function it_attaches_categories_to_product()
  {
    $product_categories = factory(Term::class, 3)->create([
      'taxonomy' => 'product_category',
    ]);

    $product = factory(Product::class)->create();

    // We haven't yet given the product a category; it should be uncategorised.
    $this->assertEquals('Uncategorised', $product->product_category->term);

    $product = $product->syncTerms($product_categories);

    $this->assertCount(3, $product->fresh()->product_categories);
  }

  /** @test **/
  public function it_uncategorises_a_product()
  {
    $product = factory(Product::class)->create();

    $product_categories = factory(Term::class, 3)->create([
      'taxonomy' => 'product_category',
    ]);

    $product = $product->syncTerms($product_categories);

    $product = $product->syncTerms([]);
    $this->assertEquals('Uncategorised', $product->product_category->term);
  }

  /** @test **/
  public function it_gets_products_on_sale()
  {
    $sale_product = factory(Product::class)->create([
      'price' => 50,
      'sale_price' => 30,
    ]);

    $normal_product = factory(Product::class)->create([
      'price' => 60,
      'sale_price' => null,
    ]);

    $sale_products = Product::onSale()->get();

    $this->assertCount(1, $sale_products);
    $this->assertEquals($sale_products->first()->name, $sale_product->name);
  }

  /** @test **/
  public function it_renders_html_in_a_product_description()
  {
    $product = factory(Product::class)->create([
      'description' => '<ul><li>this is a list item</li></ul>',
    ]);

    $this->assertContains('a list item', $product->getDescriptionHtml());
  }
}
