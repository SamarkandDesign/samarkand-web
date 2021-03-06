<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('products', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->string('status', 20)->default('published');

      $table
        ->integer('price')
        ->unsigned()
        ->default(0);
      $table
        ->integer('sale_price')
        ->unsigned()
        ->nullable();
      $table->integer('stock_qty')->nullable();
      $table
        ->string('sku')
        ->unique()
        ->nullable();

      $table->integer('user_id')->unsigned();
      $table
        ->integer('product_id')
        ->unsigned()
        ->nullable();

      $table->timestamp('published_at')->nullable();
      $table->softDeletes();
      $table->timestamps();
    });

    Schema::table('products', function ($table) {
      $table
        ->foreign('user_id')
        ->references('id')
        ->on('users');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('products', function ($table) {
      $table->dropForeign('products_user_id_foreign');
    });

    Schema::drop('products');
  }
}
