<?php

use App\Order;
use App\OrderItem;
use App\Services\Invoicing\InvoiceCreator;
use App\Services\Invoicing\XeroInvoiceCreator;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Remote\Object;

class InvoiceCreatorTest extends TestCase
{
    /** @test **/
  public function it_instantiates_an_invoice_creator()
  {
      $invoiceCreator = App::make(InvoiceCreator::class);
      $this->assertInstanceOf(XeroInvoiceCreator::class, $invoiceCreator);
  }

  /** @test **/
  public function it_creates_an_invoice_from_an_order_and_returns_the_id()
  {
      $invoiceCreator = new XeroInvoiceCreator(new FakePrivateApplication());

    // make a dummy order
    $order = factory(Order::class)->create();
      $items = factory(OrderItem::class, 3)->create();
      $order->order_items()->saveMany($items);

      $invoiceId = $invoiceCreator->createInvoice($order);
      $this->assertEquals('abc123', $invoiceId);
  }
}

class FakePrivateApplication extends PrivateApplication
{
    public function __construct()
    {
    }

    public function save(Object $object, $replace_data = false)
    {
        $object->setInvoiceId('abc123');
    }
}
