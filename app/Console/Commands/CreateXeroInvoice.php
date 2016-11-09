<?php

namespace App\Console\Commands;

use App\Order;
use App\OrderItem;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Console\Command;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Invoice\LineItem;

class CreateXeroInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xero:create-invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new invoice in xero';

    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $config = [
            'oauth' => [
                'callback'         => 'http://localhost/',
                'consumer_key'     => config('services.xero.key'),
                'consumer_secret'  => config('services.xero.secret'),
                'rsa_private_key'  => sprintf('file://%s', storage_path('certs/xero_private.pem')),
            ],
            // 'curl' => [
            //     CURLOPT_CAINFO => __DIR__.'/certs/ca-bundle.crt',
            // ],
        ];

        $this->client = new PrivateApplication($config);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // make a dummy order
        $order = factory(Order::class)->create();
        $item = factory(OrderItem::class)->create();
        $order->order_items()->save($item);

        // get the contact for the order
        $contact = new Contact($this->client);
        $contact->setName($order->user->name)
                ->setEmailAddress($order->user->email);
        
        // get the line item for the order
        $itemToUse = $order->items->first();

        $item = new LineItem($this->client);
        $item->setDescription($itemToUse->description)
             ->setQuantity($itemToUse->quantity)
             ->setUnitAmount($itemToUse->price_paid->asDecimal())
             ->setAccountCode('200');

        // build up the invoice and save it
        $invoice = new Invoice($this->client);
        $invoice->setType('ACCREC')
                ->setStatus('AUTHORISED')
                ->setDate($order->updated_at)
                ->setDueDate($order->updated_at)
                ->setContact($contact)
                ->setLineAmountType('Inclusive')
                ->addLineItem($item)
                ->save();
        dd($invoice);
    }


}
