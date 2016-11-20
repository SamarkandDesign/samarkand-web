<?php

namespace Integration;

use TestCase;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Invoice;

class InvoiceTest extends TestCase
{
  use \CreatesOrders;

  /** @test **/
  public function it_allows_a_user_to_download_a_pdf_of_an_invoice_for_their_order()
  {

    // Given I am logged in and have placed an order
    $order = $this->createOrder();
    $this->be($order->customer);

    // I can view the order page and see a link for the invoice
    $this->visit("/account/orders/{$order->id}")
         ->see('Download Invoice');

    // And click it to download the invoice
    $fakeInvoice = \Mockery::mock(Invoice::class);
    $fakeInvoice->shouldReceive('getPDF')->once()->andReturn(file_get_contents(base_path('tests/resources/files/invoice.pdf')));
    
    $xero = \Mockery::mock(PrivateApplication::class);
    $xero->shouldReceive('loadByGUID')
         ->with('Accounting\\Invoice', $order->invoice_id)
         ->once()
         ->andReturn($fakeInvoice);

    $this->app->instance(PrivateApplication::class, $xero);

    $this->get("/account/orders/{$order->id}/invoice");
    $this->assertResponseOk();
  }
}
