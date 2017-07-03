<?php

use App\Product;

class ProductImporterTest extends TestCase
{
    /** @test **/
  public function it_imports_a_csv_of_products_into_the_database()
  {
      $this->loginAsAdmin();
      $this->markTestSkipped();
      $response = $this->get('admin/products/upload')
         ->attach(base_path('tests/resources/files/products.csv'), 'file')
         ->press('Import Products')
         ->see('2 products imported');

      $this->assertDatabaseHas('products', ['name' => 'Some Product']);
  }

  /** @test **/
  public function it_validates_products_and_displays_failed_imports()
  {
      $this->loginAsAdmin();
      $this->markTestSkipped();
      $response = $this->get('admin/products/upload')
         ->attach(base_path('tests/resources/files/products_with_failure.csv'), 'file')
         ->press('Import Products')
         ->see('1 products imported')
         ->see('The following imports failed')
         ->see('The price must be at least 0');

      $this->assertDatabaseHas('products', ['name' => 'Some Product']);
  }
}
