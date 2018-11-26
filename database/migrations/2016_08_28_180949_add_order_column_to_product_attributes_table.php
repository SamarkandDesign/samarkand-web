<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddOrderColumnToProductAttributesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('product_attributes', function ($table) {
      $table
        ->integer('order')
        ->after('slug')
        ->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('product_attributes', function ($table) {
      $table->dropColumn('order');
    });
  }
}
