<?php

namespace App\Services\Invoicing;

use App\Order;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Account;
use XeroPHP\Models\Accounting\Address;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Invoice\LineItem;
use XeroPHP\Models\Accounting\Payment;

class XeroInvoiceCreator implements InvoiceCreator
{
    const INVOICE_STATUS = 'AUTHORISED';
    const INVOICE_TYPE = 'ACCREC';

    protected $xero;
    protected $invoiceAccountCode;
    protected $paymentAccountCode;

    public function __construct(PrivateApplication $xero)
    {
        $this->xero = $xero;
        $this->invoiceAccountCode = config('services.xero.invoice_account');
        $this->paymentAccountCode = config('services.xero.payment_account');
    }

    /**
     * Create an invoice for an order.
     *
     * @param  Order  $order
     *
     * @return string        The ID of the saved invoice
     */
    public function createInvoice(Order $order)
    {
        if (!is_null($order->invoice_id)) {
            throw new \Exception(sprintf('The order has already an invoice generated for it ("%s")', $order->invoice_id));
        }

        $invoice = $this->buildInvoice($order);
        $invoice->save();

        $this->createPayment($order, $invoice);

        return $invoice->getInvoiceId();
    }

    protected function buildInvoice(Order $order)
    {
        $contact = $this->getContact($order);

        $invoice = new Invoice($this->xero);
        $invoice = $invoice->setType(self::INVOICE_TYPE)
                           ->setStatus(self::INVOICE_STATUS)
                           ->setDate($order->updated_at)
                           ->setDueDate($order->updated_at)
                           ->setContact($contact)
                           ->setReference(sprintf('Order #%s', $order->id))
                           ->setLineAmountType('Inclusive');


        foreach ($this->getLineItems($order) as $item) {
            $invoice->addLineItem($item);
        }

        return $invoice;
    }

    protected function getContact(Order $order)
    {
      return (new Contact())->setName($order->user->name)
                            ->setEmailAddress($order->user->email)
                            ->addAddress($this->getAddress($order));
    }

    protected function getAddress(Order $order)
    {
      return (new Address())->setAddressType('STREET')
                            ->setAddressLine1($order->billing_address->line_1)
                            ->setAddressLine2($order->billing_address->line_2)
                            ->setAddressLine3($order->billing_address->line_3)
                            ->setCity($order->billing_address->city)
                            ->setPostalCode($order->billing_address->postcode)
                            ->setCountry($order->billing_address->country);
    }

    protected function getLineItems($order)
    {
        return $order->items->map(function ($item) {

            $code = $item->orderable ? $item->orderable->sku : '';

            return (new LineItem())->setDescription($item->description)
                                   ->setQuantity($item->quantity)
                                   ->setUnitAmount($item->price_paid->asDecimal())
                                   ->setAccountCode($this->invoiceAccountCode);
        });
    }

    /**
     * Create the corresponding payment for the invoice
     * @param  Order   $order   
     * @param  Invoice $invoice 
     * @return Payment           
     */
    protected function createPayment(Order $order, Invoice $invoice)
    {
      $account = (new Account())->setCode($this->paymentAccountCode);

      $payment = (new Payment($this->xero))->setInvoice($invoice)
                                           ->setAccount($account)
                                           ->setDate($order->updated_at)
                                           ->setAmount($order->amount->asDecimal())
                                           ->setReference($order->payment_id);
      $payment->save();
      return $payment;
    }
}
