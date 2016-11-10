<?php

namespace App\Services\Invoicing;

use App\Order;

interface InvoiceCreator {
  public function createInvoice(Order $order);
}