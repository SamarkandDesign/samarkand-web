<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('addresses', function (Blueprint $table) {
      $table->increments('id');
      $table->morphs('addressable');

      $table->string('name');

      $table->string('phone', 24)->nullable();

      $table->string('line_1');
      $table->string('line_2')->nullable();
      $table->string('line_3')->nullable();
      $table->string('city');
      $table->string('postcode');
      $table->string('country', 2);

      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('addresses');
  }
}
