<?php

class MenuItemsTest extends TestCase
{
  private $user;

  public function setUp()
  {
    parent::setUp();
    $this->user = $this->logInAsAdmin();
  }

  /** @test **/
  public function it_allows_adding_a_menu_item()
  {
    $response = $this->get('/admin/menus');

    $response = $this->followRedirects(
      $this->post('/admin/menus', [
        'menu' => 'main',
        'label' => 'My Item',
        'link' => '/my-item',
        'order' => '4',
      ])
    );

    $response->assertSee('Item Saved');
    $response->assertSee('/my-item');

    $this->assertDatabaseHas('menu_items', [
      'menu' => 'main',
      'label' => 'My Item',
      'link' => '/my-item',
      'order' => 4,
    ]);
  }

  /** @test **/
  public function it_edits_a_menu_items()
  {
    $item = factory('App\MenuItem')->create();

    $response = $this->get("admin/menus/{$item->id}/edit");
    $response->assertSee($item->label);

    $response = $this->followRedirects(
      $this->patch(
        "/admin/menus/{$item->id}",
        array_merge($item->toArray(), [
          'link' => '/whatever',
        ])
      )
    );

    $response->assertSee('Item Updated');

    $this->assertDatabaseHas('menu_items', [
      'link' => '/whatever',
    ]);
  }

  /** @test **/
  public function it_deletes_a_menu()
  {
    $item = factory('App\MenuItem')->create();

    $this->delete("/admin/menus/{$item->id}");

    $this->assertDatabaseMissing('menu_items', [
      'link' => $item->link,
    ]);
  }
}
