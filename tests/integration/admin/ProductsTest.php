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

      $response = $this->get('admin/products');

      $this->assertContains($product->name, $response->getContent());
  }

  /** @test **/
  public function it_searches_for_a_product_in_the_admin()
  {
      config(['scout.driver' => 'null']);

      $product = factory(Product::class)->create();
      $query = substr($product->description, 0, 4);

      $response = $this->get("admin/products/search?query=$query");
  }

  /** @test **/
  public function it_can_create_a_product()
  {
      $response = $this->get('admin/products/create');
      $this->assertContains('Create Product', $response->getContent());

      $terms = factory('App\Term', 2)->create(['taxonomy' => 'product_category']);

      $this->post('admin/products', [
      'name'         => 'nice product',
      'slug'         => 'nice-product',
      'description'  => 'lorem ipsum',
      'price'        => 62.50,
      'sale_price'   => 30,
      'stock_qty'    => 5,
      'listed'       => '0',
      'location'     => 'HQD',
      'featured'     => '1',
      'sku'          => 'LP345',
      'published_at' => Carbon::now()->format('Y-m-d h:i:s'),
      'user_id'      => $this->user->id,
      'terms'        => $terms->pluck('id')->toArray(),
      '_token'       => csrf_token(),
    ]);

    // dd($this->response->getContent());
    // $response->assertRedirectRoute('admin.products.edit', 1);

    $this->assertDatabaseHas('products', [
      'slug'       => 'nice-product',
      'price'      => 6250,
      'sale_price' => 3000,
      'sku'        => 'LP345',
      'featured'   => true,
    ]);

      $product = Product::whereSlug('nice-product')->first();

      $this->assertDatabaseHas('termables', ['termable_id' => $product->id, 'term_id' => $terms[0]->id]);
      $this->assertDatabaseHas('termables', ['termable_id' => $product->id, 'term_id' => $terms[1]->id]);
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

      $response = $this->get("admin/products/{$product->id}/edit");

      $this->assertContains('Edit Product', $response->getContent());

      $response = $this->patch("admin/products/{$product->id}", [
      'name'   => 'lorem ipsum',
      'terms'  => $terms->pluck('id')->toArray(),
      '_token' => csrf_token(),
    ]);

      $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'lorem ipsum']);
      $this->assertDatabaseHas('termables', ['termable_id' => $product->id, 'term_id' => $terms[0]->id]);
      $this->assertDatabaseHas('termables', ['termable_id' => $product->id, 'term_id' => $terms[1]->id]);

    // Ensure the product has only 2 terms associated to it
    $this->assertCount(2, $product->terms);

      $response->assertRedirect("admin/products/{$product->id}/edit");
      $response = $this->get('admin/products');
      $this->assertContains('lorem ipsum', $response->getContent());
  }

  /** @test **/
  public function it_can_delete_a_product()
  {
      $product = factory(Product::class)->create();

      $response = $this->delete(route('admin.products.delete', $product));

      $response->assertRedirect('admin/products');

    // assert that the product has been soft deleted
    $this->assertTrue(Product::withTrashed()->find($product->id)->trashed());

      $response = $this->get('admin/products/trash');

      $this->assertContains($product->name, $response->getContent());

    // hard delete the product
    $this->delete("/admin/products/{$product->id}");
      $this->assertDatabaseMissing('products', [
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

      $response = $this->get('/admin/products');

      $this->assertContains($product->name, $response->getContent());

      // $this->assertContains('Product restored', $response->getContent());
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
    $this->assertDatabaseHas('media', [
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
