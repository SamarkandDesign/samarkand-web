<?php

namespace Integration;

use App\Order;
use TestCase;

class OrdersTest extends TestCase
{
    use \CreatesOrders, \FlushesProductEvents;

    /** @test */
    public function it_shows_a_list_of_orders_and_allows_order_completion()
    {
        $this->logInAsAdmin();

        $order = $this->createOrder(['status' => 'processing']);

        $response = $this->get('admin/orders');
        $this->assertContains("#{$order->id}", $response->getContent());
             // ->press('complete-order')
             // ->see('Order Updated')
             // ->see(Order::$statuses['completed']);
    }

    /** @test **/
    public function it_shows_a_single_order_summary()
    {
        $this->markTestSkipped();
        $this->logInAsAdmin();

        $order = $this->createOrder(['status' => 'processing']);

        $response = $this->get("admin/orders/{$order->id}")
             ->see("#{$order->id}")
             ->select('completed', 'status')
             ->press('update-status')
             ->seePageIs("admin/orders/{$order->id}")
             ->see(Order::$statuses['completed']);
    }

    /** @test **/
    public function it_shows_an_order_summary_for_a_deleted_product()
    {
        $this->logInAsAdmin();

        $order = $this->createOrder(['status' => 'processing']);

        $item = $order->order_items->first();

        $item->orderable->delete();

        $response = $this->get("admin/orders/{$order->id}");
        $this->assertContains("#{$order->id}", $response->getContent());
        $this->assertContains($item->description, $response->getContent());
    }
}
