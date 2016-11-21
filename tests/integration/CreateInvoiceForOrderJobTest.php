<?php

use App\Jobs\CreateInvoiceForOrder;
use App\Order;
use App\OrderItem;

class CreateInvoiceForOrderJobTest extends TestCase
{
    /** @test **/
  public function it_creates_an_invoice_and_updates_the_order()
  {
      App::singleton(\App\Services\Invoicing\InvoiceCreator::class, function () {
          $xero = new FakePrivateXeroApplication();

          return new \App\Services\Invoicing\XeroInvoiceCreator($xero);
      });

      $order = factory(Order::class)->create();
      $items = factory(OrderItem::class, 3)->create();
      $order->order_items()->saveMany($items);

      dispatch(new CreateInvoiceForOrder($order));

      $this->assertEquals(FakePrivateXeroApplication::INVOICE_ID, $order->fresh()->invoice_id);
  }
}
