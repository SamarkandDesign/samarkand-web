<?php

namespace App\Services\Invoicing;

use App\Order;

class FakeInvoiceCreator implements InvoiceCreator
{
  public function getXeroClient()
  {
    return 'Fake Xero Client';
  }

  public function createInvoice(Order $order)
  {
    \Log::info(sprintf('Invoice created for order: %s', json_encode($order)));
    return 'invoiceid_123';
  }
}
