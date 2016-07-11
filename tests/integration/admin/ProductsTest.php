<?php

namespace Integration\Admin;

use App\Product;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Http\UploadedFile;

class ProductsTest extends \TestCase
{
    use \FlushesProductEvents;

    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = $this->logInAsAdmin();
    }

  /** @test **/
  public function it_can_view_a_list_of_products()
  {
      $product = factory(Product::class)->create();

      $this->visit('admin/products')
    ->see($product->name);
  }

  /** @test **/
  public function it_can_create_a_product()
  {
      $this->visit('admin/products/create')->see('Create Product');

      $terms = factory('App\Term', 2)->create(['taxonomy' => 'product_category']);

      $this->post('admin/products', [
      'name'         => 'nice product',
      'slug'         => 'nice-product',
      'description'  => 'lorem ipsum',
      'price'        => 62.50,
      'sale_price'   => 30,
      'stock_qty'    => 5,
      'sku'          => 'LP345',
      'published_at' => Carbon::now()->format('Y-m-d h:i:s'),
      'user_id'      => $this->user->id,
      'terms'        => $terms->pluck('id')->toArray(),
      '_token'       => csrf_token(),
    ]);

    // dd($this->response->getContent());
    // $this->assertRedirectedToRoute('admin.products.edit', 1);

    $this->seeInDatabase('products', [
      'slug'       => 'nice-product',
      'price'      => 6250,
      'sale_price' => 3000,
      'sku'        => 'LP345',
    ]);

      $product = Product::whereSlug('nice-product')->first();

      $this->seeInDatabase('termables', ['termable_id' => $product->id, 'term_id' => $terms[0]->id]);
      $this->seeInDatabase('termables', ['termable_id' => $product->id, 'term_id' => $terms[1]->id]);
  }

  /** @test **/
  public function it_validates_the_price_of_a_product()
  {
      $this->post('admin/products', [
      'name'         => 'nice product',
      'slug'         => 'nice-product',
      'description'  => 'lorem ipsum',
      'price'        => 62.50,
      'sale_price'   => 70,
      'stock_qty'    => 5,
      'sku'          => 'LP346',
      'published_at' => Carbon::now()->format('Y-m-d h:i:s'),
      'user_id'      => $this->user->id,
    ]);
  }

  /** @test **/
  public function it_can_update_a_product()
  {
      $product = factory(Product::class)->create();
      $terms = factory('App\Term', 2)->create(['taxonomy' => 'product_category']);

      $this->visit("admin/products/{$product->id}/edit")
    ->see('Edit Product');

      $this->patch("admin/products/{$product->id}", [
      'name'   => 'lorem ipsum',
      'terms'  => $terms->pluck('id')->toArray(),
      '_token' => csrf_token(),
    ]);

      $this->seeInDatabase('products', ['id' => $product->id, 'name' => 'lorem ipsum']);
      $this->seeInDatabase('termables', ['termable_id' => $product->id, 'term_id' => $terms[0]->id]);
      $this->seeInDatabase('termables', ['termable_id' => $product->id, 'term_id' => $terms[1]->id]);

    // Ensure the product has only 2 terms associated to it
    $this->assertCount(2, $product->terms);

      $this->assertRedirectedToRoute('admin.products.edit', $product);
      $this->visit('admin/products')->see('lorem ipsum');
  }

  /** @test **/
  public function it_can_delete_a_product()
  {
      $product = factory(Product::class)->create();

      $this->delete(route('admin.products.delete', $product));

      $this->assertRedirectedToRoute('admin.products.index');

    // assert that the product has been soft deleted
    $this->assertTrue(Product::withTrashed()->find($product->id)->trashed());

      $this->visit('admin/products/trash')
         ->see($product->name);

    // hard delete the product
    $this->delete("/admin/products/{$product->id}");
      $this->notSeeInDatabase('products', [
        'slug' => $product->slug,
        ]);
  }

  /** @test **/
  public function it_restores_a_product()
  {
      $product = factory(Product::class)->create();

      // move to trash
      $this->delete("/admin/products/{$product->id}");
      // restore
      $this->put("/admin/products/{$product->id}/restore");

      $this->visit('/admin/products')
           ->see($product->name)
           ->see('Product restored');
  }

  /** @test **/
  public function it_can_upload_an_image_to_a_product()
  {
      // Make a post
    $product = factory('App\Product')->create();

    // And we need a file
    $source_image = base_path('tests/resources/images/image-1.jpg');
      $image = base_path(sprintf('tests/resources/images/image-%s-tmp.jpg', mt_rand(1, 999)));
      copy($source_image, $image);

      $file = new UploadedFile($image, basename($image), null, null, null, true);

    // Send off the request to upload the file
    $response = $this->call('POST', route('api.products.media.store', $product->id), [], [], ['image' => $file]);

    // Ensure the image has been saved in the db and attached to our post
    $this->seeInDatabase('media', [
      'model_id'   => $product->id,
      'model_type' => 'App\Product',
      'file_name'  => basename($image),
    ]);

      foreach ($product->getMedia() as $media_item) {
          $this->assertFileExists($media_item->getPath());
      }

    // Delete the post which should also delete its associated media
    $product->delete();
  }
}
