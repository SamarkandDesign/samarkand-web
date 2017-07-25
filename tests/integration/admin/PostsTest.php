<?php

namespace Integration\Admin;

use App\Media;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Http\UploadedFile;

class PostsTest extends \TestCase
{
    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = $this->logInAsAdmin();
    }

    /** @test **/
    public function it_can_create_a_post()
    {
        // I go to the create posts page
        $postTitle = 'Awesome Post Title';
        $postContent = 'Here is some post content';

        $response = $this->get('/admin/posts/create');
        $response->assertStatus(200);

        $this->post('admin/posts', [
            'title'        => $postTitle,
            'slug'         => str_slug($postTitle),
            'content'      => $postContent,
            'published_at' => Carbon::now(),
            'user_id'      => $this->user->id,
            '_token'       => csrf_token(),
            ]);
        // And see that I created a post successfully
        $this->assertDatabaseHas('posts', [
            'title'   => $postTitle,
            'content' => $postContent,
            'user_id' => $this->user->id,
            ]);
    }

    /** @test **/
    public function it_can_edit_a_post()
    {

        // And a post exists in the database
        $post = factory('App\Post')->create();

        // I update the post
        $postTitle = 'Edited Title';

        $response = $this->get("/admin/posts/{$post->id}/edit");
        $this->assertContains('Edit Post', $response->getContent());

        $this->patch("admin/posts/{$post->id}", [
            'title'  => $postTitle,
            'slug'   => str_slug($postTitle),
            '_token' => csrf_token(),
            ]);
        //dd($this->response->getContent());

        // And see that I edited the post successfully
        $this->assertDatabaseHas('posts', [
            'id'      => $post->id,
            'title'   => $postTitle,
            ]);
    }

    /** @test **/
    public function it_trashes_a_post()
    {
        $post = factory('App\Post')->create();

        $response = $this->get('/admin/posts');
        $this->assertContains($post->title, $response->getContent());

        // move to trash
        $this->delete("/admin/posts/{$post->id}");
        $response->assertSessionHas('alert', 'Post moved to trash');
    }

    /** @test **/
    public function it_permanently_deletes_a_post()
    {
        $post = factory('App\Post')->create([
            'deleted_at' => Carbon::now()->subDay(),
        ]);

        $response = $this->get('/admin/posts');
        $this->assertNotContains($post->title, $response->getContent());

        $response = $this->get('admin/posts/trash');
        $this->assertContains($post->title, $response->getContent());

        // Delete permanently
        $this->delete("/admin/posts/{$post->id}");
        $response->assertSessionHas('alert', 'Post permanently deleted');

        $this->assertDatabaseMissing('posts', [
            'title' => $post->title,
            ]);
    }

    /** @test **/
    public function it_restores_a_post()
    {
        $post = factory('App\Post')->create();

        // move to trash
        $this->delete("/admin/posts/{$post->id}");
        // restore
        $this->put("/admin/posts/{$post->id}/restore");

        $response = $this->get('/admin/posts');
        $this->assertContains($post->title, $response->getContent());
        // $this->assertContains('Post restored', $response->getContent());
    }

    /** @test **/
    public function it_can_upload_an_image_to_a_post()
    {
        // Make a post
        $post = factory('App\Post')->create();

        // And we need a file
        $source_image = base_path('tests/resources/images/image-1.jpg');
        $image = base_path('tests/resources/images/image-1-tmp.jpg');
        copy($source_image, $image);

        $file = new UploadedFile($image, basename($image), null, null, null, true);

        // Send off the request to upload the file
        $response = $this->call('POST', route('api.posts.media.store', $post), [], [], ['image' => $file]);

        // An Image instance (in JSON) should be returned
        $responseData = json_decode($response->getContent());

        // Ensure the image has been saved in the db and attached to our post
        $this->assertDatabaseHas('media', [
            'model_id'   => $post->id,
            'model_type' => 'App\Post',
            'file_name'  => basename($image),
            ]);

        foreach ($post->getMedia() as $media_item) {
            $this->assertFileExists($media_item->getPath());
        }

        // Delete the post which should also delete its associated media
        $post->delete();
    }
}
