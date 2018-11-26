<?php

namespace Integration\Admin;

use App\Page;
use Carbon\Carbon;

class PagesTest extends \TestCase
{
  use \SeedsPages;

  private $user;

  public function setUp()
  {
    parent::setUp();
    $this->user = $this->logInAsAdmin();
  }

  /** @test **/
  public function it_can_create_a_page()
  {
    $parentPage = factory(Page::class)->create(['slug' => 'parent']);

    $response = $this->get('admin/pages/create');

    // Simulate submitting a form to create a page
    $response = $this->call('POST', 'admin/pages', [
      'title' => 'My First Title',
      'slug' => 'my-first-title',
      'content' => 'Some awesome content',
      'parent_id' => $parentPage->id,
      'status' => 'published',
      'user_id' => $this->user->id,
      'published_at' => '2016-03-14T16:36:15',
    ]);

    $newlyCreatePage = Page::where('slug', 'my-first-title')->first();
    $response->assertRedirect("admin/pages/{$newlyCreatePage->id}/edit");

    $this->assertDatabaseHas('pages', ['slug' => 'my-first-title']);

    $this->assertCount(1, $parentPage->children);

    $this->assertEquals('parent/my-first-title', $parentPage->children()->first()->path);
  }

  /** @test **/
  public function it_recalculates_paths_when_a_slug_is_changed()
  {
    $pages = $this->seedNestedPages();

    $response = $this->get("admin/pages/{$pages[1]->id}/edit");

    // Simulate submitting a form to create a page
    $response = $this->call('PATCH', "admin/pages/{$pages[1]->id}", [
      'title' => $pages[1]->title,
      'slug' => 'new-child-slug',
      'content' => 'Some awesome content',
      'parent_id' => $pages[0]->id,
      'status' => 'published',
      'user_id' => $pages[1]->user_id,
      'published_at' => '2016-03-14T16:36:15',
    ]);

    $this->assertEquals('first/new-child-slug', $pages[1]->fresh()->path);
    $this->assertEquals('first/new-child-slug/third', $pages[2]->fresh()->path);
  }

  /** @test **/
  public function it_recalculates_paths_when_a_parent_is_changed()
  {
    $pages = $this->seedNestedPages();

    // Make another root page
    $pages[] = factory(Page::class)->create(['slug' => 'parent2']);

    $response = $this->get("admin/pages/{$pages[1]->id}/edit");

    // Edit the first child of the original root
    $response = $this->call('PATCH', "admin/pages/{$pages[1]->id}", [
      'title' => $pages[1]->title,
      'slug' => $pages[1]->slug,
      'content' => 'Some awesome content',
      'parent_id' => $pages[3]->id, // update the parent
      'status' => 'published',
      'user_id' => $pages[1]->user_id,
      'published_at' => '2016-03-14T16:36:15',
    ]);

    $this->assertEquals("parent2/{$pages[1]->slug}", $pages[1]->fresh()->path);
    $this->assertEquals("parent2/{$pages[1]->slug}/third", $pages[2]->fresh()->path);
  }

  /** @test **/
  public function it_does_not_allow_making_a_page_a_child_of_its_children()
  {
    $pages = $this->seedNestedPages();

    $response = $this->get("admin/pages/{$pages[0]->id}/edit");

    $response = $this->call('PATCH', "admin/pages/{$pages[1]->id}", [
      'title' => $pages[1]->title,
      'slug' => 'new-child-slug',
      'content' => 'Some awesome content',
      'parent_id' => $pages[2]->id,
      'status' => 'published',
      'user_id' => $pages[1]->user_id,
      'published_at' => '2016-03-14T16:36:15',
    ]);

    // $response->assertSessionHasErrors();
    $response->assertRedirect("admin/pages/{$pages[0]->id}/edit");
  }

  /** @test **/
  public function it_trashes_a_page()
  {
    $page = factory('App\Page')->create();

    $response = $this->get('/admin/pages');
    $this->assertContains($page->title, $response->getContent());

    // move to trash
    $this->delete("/admin/pages/{$page->id}");
    $response->assertSessionHas('alert', 'Page moved to trash');
  }

  /** @test **/
  public function it_permanently_deletes_a_page()
  {
    $page = factory('App\Page')->create([
      'deleted_at' => Carbon::now()->subDay(),
    ]);

    $response = $this->get('/admin/pages');
    $this->assertNotContains($page->title, $response->getContent());

    $response = $this->get('admin/pages/trash');
    $this->assertContains($page->title, $response->getContent());

    // Delete permanently
    $this->delete("/admin/pages/{$page->id}");
    $response->assertSessionHas('alert', 'Page permanently deleted');

    $this->assertDatabaseMissing('pages', [
      'title' => $page->title,
    ]);
  }

  /** @test **/
  public function it_restores_a_page()
  {
    $page = factory('App\Page')->create();

    // move to trash
    $this->delete("/admin/pages/{$page->id}");
    // restore
    $this->put("/admin/pages/{$page->id}/restore");

    $response = $this->get('/admin/pages');
    $this->assertContains($page->title, $response->getContent());
    // $this->assertContains('Page restored', $response->getContent());
  }
}
