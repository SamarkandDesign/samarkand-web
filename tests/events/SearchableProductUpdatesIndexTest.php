<?php

namespace Events;

use App\Product;
use TestCase;

class SearchableProductUpdatesIndexTest extends TestCase
{
    /** @test */
    public function it_updates_the_search_index_when_product_created()
    {
        \SearchIndex::shouldReceive('upsertToIndex')->once();

        $product = Product::create([
          'name'        => 'Foo Product',
          'slug'        => 'foo-product',
          'description' => 'An example product',
          'sku'         => 'EP123',
          'stock_qty'   => 1,
          'price'       => 26.45,
          'sale_price'  => 12.67,
          'user_id'     => 1,
        ]);
    }

    /** @test */
    public function it_updates_the_search_index_when_product_updated()
    {
        \SearchIndex::shouldReceive('upsertToIndex')->once();
        $product = factory(Product::class)->create();

        \SearchIndex::shouldReceive('upsertToIndex')->once();
        $product->update(['name' => 'Updated Product']);
    }

    /** @test */
    public function it_removes_the_product_from_the_search_index_when_deleted()
    {
        \SearchIndex::shouldReceive('upsertToIndex')->once();
        $product = factory(Product::class)->create();

        \SearchIndex::shouldReceive('removeFromIndex')->once();
        $product->delete();
    }
}
