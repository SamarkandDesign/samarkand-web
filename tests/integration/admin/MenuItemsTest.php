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
    $this->visit('/admin/menus')
         ->type('main', 'menu')
         ->type('My Item', 'label')
         ->type('/my-item', 'link')
         ->type('4', 'order')
         ->press('Save')
         ->see('Item Saved')
         ->see('/my-item');

    $this->seeInDatabase('menu_items', [
      'menu' => 'main',
      'label' => 'My Item',
      'link'  => '/my-item',
      'order' => 4,
      ]);
  }

  /** @test **/
  public function it_edits_a_menu_items()
  {
    $item = factory('App\MenuItem')->create();

    $this->visit("admin/menus/{$item->id}/edit")
         ->see($item->label)
         ->type('/whatever', 'link')
         ->press('Update')
         ->seePageIs('/admin/menus')
         ->see('Item Updated');

    $this->seeInDatabase('menu_items', [
      'link'  => '/whatever',
      ]);
  }

  /** @test **/
  public function it_deletes_a_menu()
  {
    $item = factory('App\MenuItem')->create();

    $this->delete("/admin/menus/{$item->id}");

    $this->dontSeeInDatabase('menu_items', [
      'link'  => $item->link,
    ]);
  }
}
