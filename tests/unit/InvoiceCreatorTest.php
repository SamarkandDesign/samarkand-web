<?php

use App\Order;
use App\OrderItem;
use App\Services\Invoicing\XeroInvoiceCreator;

class InvoiceCreatorTest extends TestCase
{
    /** @test **/
  public function it_creates_an_invoice_from_an_order_and_returns_the_id()
  {
      $invoiceCreator = new XeroInvoiceCreator(new FakePrivateXeroApplication());

      $order = $this->makeOrder();

      $invoiceId = $invoiceCreator->createInvoice($order);
      $this->assertEquals(FakePrivateXeroApplication::INVOICE_ID, $invoiceId);
  }

  /** @test **/
  public function it_takes_exception_to_an_order_that_already_has_an_invoice()
  {
      $invoiceCreator = new XeroInvoiceCreator(new FakePrivateXeroApplication());
      $order = $this->makeOrder();
      $order->update(['invoice_id' => FakePrivateXeroApplication::INVOICE_ID]);

      $this->setExpectedException(Exception::class);

      $invoiceId = $invoiceCreator->createInvoice($order);
  }

    protected function makeOrder()
    {
        $order = factory(Order::class)->create();
        $items = factory(OrderItem::class, 3)->create();
        $order->order_items()->saveMany($items);

        return $order;
    }
}
