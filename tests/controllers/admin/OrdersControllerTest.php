<?php

namespace App\Http\Controllers\Admin;

use App\Order;

class OrdersControllerTest extends \TestCase
{
  use \CreatesOrders, \FlushesProductEvents;

  public function setUp()
  {
    parent::setUp();
    $this->logInAsAdmin();
  }

  /** @test **/
  public function it_shows_a_single_order()
  {
    $order = $this->createOrder();

    $response = $this->get("admin/orders/{$order->id}");

    $this->assertContains("Order #{$order->id} Details", $response->getContent());
  }

  /** @test **/
  public function it_completes_an_order()
  {
    $order = $this->createOrder(['status' => Order::PAID]);

    $response = $this->get('admin/orders');

    $this->assertContains(ucwords(Order::PAID), $response->getContent());
    // TODO: Fix this
    //  ->press('complete-order')
    //  ->seePageIs('admin/orders')
    //  $this->assertContains(ucwords(Order::COMPLETED), $response->getContent());
  }

  /** @test **/
  public function it_shows_only_orders_of_a_certain_status()
  {
    $order_1 = $this->createOrder(['status' => Order::PAID]);
    $order_2 = $this->createOrder(['status' => Order::COMPLETED]);

    $response = $this->get('admin/orders/' . Order::PAID);

    $this->assertContains("#{$order_1->id}", $response->getContent());
    $this->assertNotContains("#{$order_2->id}", $response->getContent());
  }
}
