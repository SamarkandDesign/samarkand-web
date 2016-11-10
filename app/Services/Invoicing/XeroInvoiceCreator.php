<?php

namespace App\Services\Invoicing;

use App\Order;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Invoice\LineItem;

class XeroInvoiceCreator implements InvoiceCreator
{
    const ACCOUNT_CODE = '200';
    const INVOICE_STATUS = 'AUTHORISED';
    const INVOICE_TYPE = 'ACCREC';

    protected $xero;

    public function __construct(PrivateApplication $xero)
    {
        $this->xero = $xero;
    }

    public function createInvoice(Order $order)
    {
        $invoice = $this->buildInvoice($order);
        $invoice->save();

        return $invoice->getInvoiceId();
    }

    protected function buildInvoice(Order $order)
    {
        $contact = (new Contact())->setName($order->user->name)
                            ->setEmailAddress($order->user->email);

    // build up the invoice
    $invoice = new Invoice($this->xero);
        $invoice = $invoice->setType(self::INVOICE_TYPE)
                       ->setStatus(self::INVOICE_STATUS)
                       ->setDate($order->updated_at)
                       ->setDueDate($order->updated_at)
                       ->setContact($contact)
                       ->setReference($order->payment_id)
                       ->setLineAmountType('Inclusive');

    // add line items
    foreach ($this->getLineItems($order) as $item) {
        $invoice->addLineItem($item);
    }

        return $invoice;
    }

    protected function getLineItems($order)
    {
        return $order->items->map(function ($item) {
            return (new LineItem())->setDescription($item->description)
                           ->setQuantity($item->quantity)
                           ->setUnitAmount($item->price_paid->asDecimal())
                           ->setAccountCode(self::ACCOUNT_CODE);
        });
    }
}
