<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\ViewOrderRequest;
use App\Order;
use XeroPHP\Application\PrivateApplication;

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
