<?php

namespace App\Repositories\Product;

use App\Product;
use App\Term;
use TestCase;

class ProductRepositoryTest extends TestCase
{
    use \FlushesProductEvents;

    private $products;

    public function setUp()
    {
        parent::setUp();
        $this->products = app(ProductRepository::class);
    }

    /** @test **/
    public function it_counts_the_products()
    {
        factory(Product::class, 2)->create();

        $this->assertEquals(2, $this->products->count());
    }

    /** @test **/
    public function it_counts_the_products_low_in_stock()
    {
        config(['shop.low_stock_qty' => 3]);

        factory(Product::class, 2)->create(['stock_qty' => 10]);
        factory(Product::class, 2)->create(['stock_qty' => 2]);
        factory(Product::class, 3)->create(['stock_qty' => 0]);

        $this->assertEquals(2, $this->products->countLowStock());
    }

    /** @test **/
    public function it_counts_the_products_out_of_stock()
    {
        factory(Product::class, 2)->create(['stock_qty' => 2]);
        factory(Product::class, 3)->create(['stock_qty' => 0]);

        $this->assertEquals(3, $this->products->countOutOfStock());
    }

    /** @test */
    public function it_gets_products_for_the_shop_page()
    {
        // set up some products with attribute property and categories
        $basicProducts = factory(Product::class, 4)->create(['stock_qty' => 2]);

        $categorisedProduct = factory(Product::class)->create(['stock_qty' => 2]);
        $productCategory = factory('App\Term')->create(['taxonomy' => 'product_category']);
        $categorisedProduct->product_categories()->attach($productCategory);

        $outOfStockProducts = factory(Product::class, 3)->create(['stock_qty' => 0]);

        // now test the results
        // first a basic query
        $q1 = $this->products->shopProducts((new Term()));
        $this->assertCount(5, $q1);

        // now for a product category
        $q2 = $this->products->shopProducts($productCategory);
        $this->assertCount(1, $q2);
    }

    /** @test **/
    public function it_gets_the_locations_of_products()
    {
        $product1 = factory(Product::class, 2)->create(['location' => 'My House']);
        $product2 = factory(Product::class)->create(['location' => 'Down Under']);

        $locations = $this->products->getLocations();

        $this->assertContains('My House', $locations);
        $this->assertCount(2, $locations);
    }
}
