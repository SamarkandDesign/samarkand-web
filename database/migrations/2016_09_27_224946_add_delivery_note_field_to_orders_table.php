<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryNoteFieldToOrdersTable extends Migration
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
        ->text('delivery_note')
        ->after('shipping_address_id')
        ->default('');
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
      $table->dropColumn('delivery_note');
    });
  }
}
