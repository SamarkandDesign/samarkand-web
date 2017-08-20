<?php

use App\Product;
use Illuminate\Http\UploadedFile;

class ProductImporterTest extends TestCase
{
    /** @test **/
    public function it_imports_a_csv_of_products_into_the_database()
    {
        $this->loginAsAdmin();
        $response = $this->get('admin/products/upload');
        $path = base_path('tests/resources/files/products.csv');

        $file = new UploadedFile($path, 'products.csv', filesize($path), 'text/csv', null, true);

        $response = $this->followRedirects($this->call('POST', '/admin/products/upload', [], [], [
        'file' => $file,
        ]));

        $response->assertSee('2 products imported');

        $this->assertDatabaseHas('products', ['name' => 'Some Product']);
    }

    /** @test **/
    public function it_validates_products_and_displays_failed_imports()
    {
        $this->loginAsAdmin();

        $path = base_path('tests/resources/files/products_with_failure.csv');
        $file = new UploadedFile($path, 'products_with_failure.csv', filesize($path), 'text/csv', null, true);

        $response = $this->followRedirects($this->call('POST', '/admin/products/upload', [], [], [
        'file' => $file,
        ]));

        $response->assertSee('1 products imported');
        $response->assertSee('The following imports failed');
        $response->assertSee('The price must be at least 0');

        $this->assertDatabaseHas('products', ['name' => 'Some Product']);
    }
}
