<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Invoicing\InvoiceCreator;

class CheckXeroAccess extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'xero:check-access';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Check the application is able to talk to Xero';

  protected $xero;

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct(InvoiceCreator $creator)
  {
    parent::__construct();
    $this->xero = $creator->getXeroClient();
  }

  /**
   * Execute the console command.
   * If no exception is thrown, we know we have proper access to our xero account.
   *
   * @return mixed
   */
  public function handle()
  {
    $contacts = $this->xero
      ->load('Accounting\\Contact')
      ->page(1)
      ->execute();

    $this->info('Connection to Xero succeeded!');
  }
}
