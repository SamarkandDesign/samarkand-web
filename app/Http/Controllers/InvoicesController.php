<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\Order\ViewOrderRequest;
use App\Order;
use Illuminate\Http\Request;
use XeroPHP\Application\PrivateApplication;

class InvoicesController extends Controller
{
    public function show(ViewOrderRequest $request, Order $order, PrivateApplication $xero)
    {
      $invoice = $xero->loadByGUID('Accounting\\Invoice', $order->invoice_id);
      return \Response::make($invoice->getPDF(), 200, ['content-type'=>'application/pdf']);
    }

}
