<?php

namespace App\Http\Controllers;

use App\Order;
use XeroPHP\Application\PrivateApplication;
use App\Http\Requests\Order\ViewOrderRequest;

class InvoicesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function show(ViewOrderRequest $request, Order $order, PrivateApplication $xero)
  {
    if (!$order->invoice_id) {
      abort(404);
    }

    $invoice = $xero->loadByGUID('Accounting\\Invoice', $order->invoice_id);

    return \Response::make($invoice->getPDF(), 200, ['content-type' => 'application/pdf']);
  }
}
