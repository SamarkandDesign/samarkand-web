<?php

namespace Integration;

use App\Page;
use TestCase;

class PagesTest extends TestCase
{
  use \SeedsPages;

  /** @test **/
  public function it_loads_a_page_by_slug()
  {
    $page = factory('App\Page')->create([
      'slug' => 'my-very-nice-page',
      'meta_description' => 'The quick brown fox is hairy',
    ]);

    $response = $this->get('/my-very-nice-page');
    $this->assertContains('content="The quick brown fox', $response->getContent());
    $this->assertContains($page->content, $response->getContent());
  }

  /** @test **/
  public function it_gets_the_permalink_for_a_nested_page()
  {
    $pages = $this->seedNestedPages();

    $this->assertEquals('first/second/third', $pages[2]->path);
    $this->assertEquals('first/second', $pages[2]->getPath(($excludeSelf = true)));
  }

  /** @test **/
  public function it_gets_the_permalink_for_an_unnested_page()
  {
    $page = factory(Page::class)->create(['slug' => 'first']);
    $this->assertEquals('first', $page->path);
    $this->assertEquals('', $page->getPath(true));
  }

  /** @test **/
  public function it_shows_the_correct_page_from_nested_slugs()
  {
    $pages = $this->seedNestedPages();

    $response = $this->get('/first/second');
    $this->assertContains($pages[1]->title, $response->getContent());
    $this->assertContains($pages[1]->content, $response->getContent());

    $response = $this->get('/first/second/third');
    $this->assertContains($pages[2]->title, $response->getContent());
    $this->assertContains($pages[2]->content, $response->getContent());
  }

  /** @test **/
  public function it_updates_the_path_for_child_pages()
  {
    $pages = $pages = $this->seedNestedPages();

    // Add child to the 3rd page
    $child = factory(Page::class)->create(['slug' => 'child']);

    $this->assertEquals('child', $child->path);

    $child->makeChildOf($pages[2]);

    $this->assertEquals('first/second/third/child', $child->fresh()->path);

    // Now move the parent page to become the child of the root page
    $pages[2]->makeChildOf($pages[0]);

    // Check the path to the has been updated
    $this->assertEquals('first/third/child', $child->fresh()->path);
  }

  /** @test **/
  public function it_doesnt_find_non_existant_pages()
  {
    $this->seedNestedPages();

    $response = $this->call('GET', '/first/second/no-page-here');
    $this->assertEquals(404, $response->status());

    $response = $this->call('GET', '/first/second/no-page-here/at-this-level-either');
    $this->assertEquals(404, $response->status());
  }

  /** @test **/
  public function it_doesnt_show_unpublished_pages()
  {
    $pages = $this->seedNestedPages();

    $pages[1]->update(['status' => 'draft']);

    $response = $this->call('GET', '/first/second');

    $this->assertEquals(404, $response->status());
  }
}
