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

        $this->visit('admin/media')
             ->see($images->first()->title);

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

        $this->visit('admin/media')
             ->see($image->title);
    }

    /** @test **/
    public function it_allows_deleting_a_media_item()
    {
        $this->logInAsAdmin();

        $image = $this->createImage();

        // simulate clicking 'delete' for a given media item
        $this->delete("admin/media/{$image->id}");

        $this->assertRedirectedTo('admin/media');
        $this->assertSessionHas(['alert' => 'Image Deleted']);

        $this->notSeeInDatabase('media', ['name' => $image->name]);
    }
}
