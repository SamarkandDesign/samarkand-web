<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceIdColumnToOrders extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('orders', function ($table) {
      $table
        ->string('invoice_id')
        ->after('payment_id')
        ->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('orders', function ($table) {
      $table->dropColumn('invoice_id');
    });
  }
}
