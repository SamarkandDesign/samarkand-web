<?php

namespace Integration;

use TestCase;
use App\Order;
use Illuminate\Support\Facades\Notification;

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
    $this->logInAsAdmin();

    $order = $this->createOrder(['status' => 'processing']);

    $response = $this->get("admin/orders/{$order->id}");
    $response->assertSee("#{$order->id}");

    $response = $this->patch("admin/orders/{$order->id}", [
      'status' => 'completed',
    ]);
    $response->assertRedirect("admin/orders/{$order->id}");
    $this->followRedirects($response)->assertSee(Order::$statuses['completed']);
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

  /** @test **/
  public function it_sends_a_dispatch_notification_for_an_order_with_tracking_link()
  {
    Notification::fake();
    $this->logInAsAdmin();

    $order = $this->createOrder(['status' => 'processing']);

    $response = $this->get("admin/orders/{$order->id}");

    $this->assertContains('Send Dispatch Confirmation', $response->getContent());

    $response = $this->post("/admin/orders/{$order->id}/dispatch_confirmations", [
      'tracking_link' => 'http://tracking-link',
    ]);
    $response->assertRedirect("admin/orders/{$order->id}");
    $response = $this->followRedirects($response);

    $content = $response->getContent();
    $this->assertContains('Confirmation sent', $content);
    $this->assertContains(
      sprintf(
        'Dispatch confirmation sent to %s with tracking link http://tracking-link',
        $order->user->email
      ),
      $content
    );
    Notification::assertSentTo($order->user, \App\Notifications\OrderDispatched::class);
  }
}
