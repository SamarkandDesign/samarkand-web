<?php

namespace App\Console\Commands;

use App\Services\Invoicing\InvoiceCreator;
use Illuminate\Console\Command;

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

    protected $invoiceCreator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(InvoiceCreator $invoiceCreator)
    {
        parent::__construct();
        $this->invoiceCreator = $invoiceCreator;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $order = \App\Order::where('status', 'completed')->get()->first();
        $result = $this->invoiceCreator->createInvoice($order);
        dd($result);
    }
}
