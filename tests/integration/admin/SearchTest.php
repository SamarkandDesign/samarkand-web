<?php

namespace Integration\Admin;

use App\Product;
use App\Repositories\Product\ProductRepository;

class SearchTest extends \TestCase
{
    protected function setUp()
    {
        parent::setUp();
        config(['shop.products_per_page' => 5]);
        $this->products = factory(Product::class, 10)->create([
      'listed'    => true,
      'stock_qty' => 5,
      ]);
        $this->useFakeProductRepo('somequery', $this->products);
    }

    /** @test **/
    public function it_paginates_product_search_results_in_the_admin_area()
    {
        $this->logInAsAdmin();
        $response = $this->get('/admin/products/search?query=somequery');
        $this->assertContains($this->products[3]->name, $response->getContent());
        $this->assertNotContains($this->products[8]->name, $response->getContent());

        $response = $this->get('/admin/products/search?query=somequery&page=2');

        $response->assertSee($this->products[8]->name);
    }

    /** @test **/
    public function it_paginates_product_search_results_in_the_shop_area()
    {
        $this->products[1]->update(['stock_qty' => 0]);
        $this->products[9]->update(['listed' => false]);

        $response = $this->get('/shop/search?query=somequery');
        $this->assertContains($this->products[3]->name, $response->getContent());
        $this->assertNotContains($this->products[1]->name, $response->getContent());
        $this->assertNotContains($this->products[8]->name, $response->getContent());

        $response = $this->get('/shop/search?query=somequery&page=2');

        $response->assertSee($this->products[8]->name);
        $response->assertDontSee($this->products[9]->name);
    }

    protected function useFakeProductRepo($query, $results)
    {
        $productRepo = \Mockery::mock(ProductRepository::class);
        $productRepo->shouldReceive('search')->with($query)->andReturn($results);
        \App::instance(ProductRepository::class, $productRepo);
    }
}
