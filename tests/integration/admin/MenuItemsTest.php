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
    $this->visit('/admin/menus');
  }
}
