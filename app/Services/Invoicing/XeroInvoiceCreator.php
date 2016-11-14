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

  /**
   * Create an invoice for an order
   * @param  Order  $order 
   * @return String        The ID of the saved invoice
   */
  public function createInvoice(Order $order)
  {
    if (!is_null($order->invoice_id)) {
      throw new \Exception(sprintf('The order has already an invoice generated for it ("%s")', $order->invoice_id));
    }

    $invoice = $this->buildInvoice($order);
    $invoice->save();

    return $invoice->getInvoiceId();
  }

  protected function buildInvoice(Order $order)
  {
    $contact = (new Contact())->setName($order->user->name)
    ->setEmailAddress($order->user->email);

    $invoice = new Invoice($this->xero);
    $invoice = $invoice->setType(self::INVOICE_TYPE)
    ->setStatus(self::INVOICE_STATUS)
    ->setDate($order->updated_at)
    ->setDueDate($order->updated_at)
    ->setContact($contact)
    ->setReference($order->payment_id)
    ->setLineAmountType('Inclusive');


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
