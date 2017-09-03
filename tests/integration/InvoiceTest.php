<?php

namespace Integration;

use TestCase;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Application\PrivateApplication;

class InvoiceTest extends TestCase
{
    use \CreatesOrders;

    /** @test **/
    public function it_allows_a_user_to_download_a_pdf_of_an_invoice_for_their_order()
    {

    // Given I am logged in and have placed an order
        $order = $this->createOrder();
        $order->update(['invoice_id' => str_random(10)]);
        $this->be($order->customer);

        // I can view the order page and see a link for the invoice
        $response = $this->get("/account/orders/{$order->id}");
        $this->assertContains('Download Invoice', $response->getContent());

        // And click it to download the invoice
        $fakeInvoice = \Mockery::mock(Invoice::class);
        $fakeInvoice->shouldReceive('getPDF')->once()->andReturn(file_get_contents(base_path('tests/resources/files/invoice.pdf')));

        $xero = \Mockery::mock(PrivateApplication::class);
        $xero->shouldReceive('loadByGUID')
         ->with('Accounting\\Invoice', $order->invoice_id)
         ->once()
         ->andReturn($fakeInvoice);

        $this->app->instance(PrivateApplication::class, $xero);

        $response = $this->get("/account/orders/{$order->id}/invoice");
        $response->assertStatus(200);
    }

    /** @test **/
    public function it_returns_not_found_if_no_invoice_id_exists()
    {
        $order = $this->createOrder();
        $this->be($order->customer);
        $response = $this->get("/account/orders/{$order->id}/invoice");
        $response->assertStatus(404);
    }
}
