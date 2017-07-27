<?php

use App\Product;

class ProductImporterTest extends TestCase
{
    /** @test **/
  public function it_imports_a_csv_of_products_into_the_database()
  {
      $this->loginAsAdmin();

      $this->visit('admin/products/upload')
         ->attach(base_path('tests/resources/files/products.csv'), 'file')
         ->press('Import Products')
         ->see('2 products imported');

      $this->seeInDatabase('products', ['name' => 'Some Product']);
  }

  /** @test **/
  public function it_validates_products_and_displays_failed_imports()
  {
      $this->loginAsAdmin();

      $this->visit('admin/products/upload')
         ->attach(base_path('tests/resources/files/products_with_failure.csv'), 'file')
         ->press('Import Products')
         ->see('1 products imported')
         ->see('The following imports failed')
         ->see('The price must be at least 0');

      $this->seeInDatabase('products', ['name' => 'Some Product']);
  }
}
