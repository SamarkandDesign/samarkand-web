<?php

use App\Http\Requests\Product\CreateProductRequest;
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

    // $request = new CreateProductRequest();
    // $rules = $request->rules();

    // $csv = collect(file(base_path('tests/resources/files/products.csv')))->map(function($line) {
    //   return str_getcsv($line);
    // });

    // $headings = $csv->first();
    // $data = $csv->except([0])->map(function($item) use ($headings) {
    //   return array_combine($headings, $item);
    // })->filter(function ($item) use ($rules) {
    //   return Validator::make($item, $rules)->passes();
    // });

    // dd($data);
  }
}