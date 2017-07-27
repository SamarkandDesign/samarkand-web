<?php

namespace App\Http\Controllers\Admin;

use App\Media;

class MediaControllerTest extends \TestCase
{
    /** @test **/
    public function it_shows_the_media_index_page()
    {
        $this->logInAsAdmin();

        // new up some images
        $images = $this->createImage(3);

        $response = $this->get('/admin/media');

        $this->assertContains($images->first()->name, $response->getContent());

        $images->first()->model->forceDelete();
    }

    /** @test **/
    public function it_shows_the_media_page_when_a_parent_is_deleted()
    {
        $this->logInAsAdmin();

        // new up an image
        $image = $this->createImage();

        $model = $image->model;
        $model->forceDelete();

        $response = $this->get('/admin/media');
        $this->assertNotContains($image->name, $response->getContent());
    }

    /** @test **/
    public function it_allows_deleting_a_media_item()
    {
        $this->logInAsAdmin();

        $image = $this->createImage();

        // simulate clicking 'delete' for a given media item
        $response = $this->delete("admin/media/{$image->id}");

        $response->assertRedirect('admin/media');

        $response->assertSessionHas('alert', 'Image Deleted');

        $this->assertDatabaseMissing('media', ['name' => $image->name]);
    }
}
