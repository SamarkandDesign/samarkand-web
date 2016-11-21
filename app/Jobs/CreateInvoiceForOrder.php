<?php

namespace App\Jobs;

use App\Order;
use App\Services\Invoicing\InvoiceCreator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateInvoiceForOrder implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(InvoiceCreator $invoiceCreator)
    {
        $invoiceId = $invoiceCreator->createInvoice($this->order);

        $this->order->update(['invoice_id' => $invoiceId]);
    }
}
