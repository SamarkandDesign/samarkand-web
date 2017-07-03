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
      $this->markTestSkipped();
      $response = $this->get('/admin/menus')
         ->type('main', 'menu')
         ->type('My Item', 'label')
         ->type('/my-item', 'link')
         ->type('4', 'order')
         ->press('Save')
         ->see('Item Saved')
         ->see('/my-item');

      $this->assertDatabaseHas('menu_items', [
      'menu'  => 'main',
      'label' => 'My Item',
      'link'  => '/my-item',
      'order' => 4,
      ]);
  }

  /** @test **/
  public function it_edits_a_menu_items()
  {
      $this->markTestSkipped();
      $item = factory('App\MenuItem')->create();

      $response = $this->get("admin/menus/{$item->id}/edit")
         ->see($item->label)
         ->type('/whatever', 'link')
         ->press('Update')
         ->seePageIs('/admin/menus')
         ->see('Item Updated');

      $this->assertDatabaseHas('menu_items', [
      'link'  => '/whatever',
      ]);
  }

  /** @test **/
  public function it_deletes_a_menu()
  {
      $item = factory('App\MenuItem')->create();

      $this->delete("/admin/menus/{$item->id}");

      $this->assertDatabaseMissing('menu_items', [
      'link'  => $item->link,
    ]);
  }
}
