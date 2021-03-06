<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    // 'App\Console\Commands\Inspire',
    \App\Console\Commands\CancelAbandonedOrders::class,
    \App\Console\Commands\RefreshSearchIndex::class,
    \App\Console\Commands\InitialiseApp::class,
    \App\Console\Commands\CreateXeroInvoice::class,
    \App\Console\Commands\CheckXeroAccess::class,
    \App\Console\Commands\GenerateProductFeed::class,
  ];

  /**
   * Define the application's command schedule.
   *
   * @param \Illuminate\Console\Scheduling\Schedule $schedule
   *
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    $schedule->command('orders:cancel-abandoned')->everyThirtyMinutes();
  }
}
