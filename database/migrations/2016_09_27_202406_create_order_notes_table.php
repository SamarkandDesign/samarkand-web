<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateOrderNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_notes', function ($table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->string('key')->nullable();
            $table->string('body');
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamp('created_at');
        });

        Schema::table('order_notes', function ($table) {
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_notes', function ($table) {
            $table->dropForeign('order_notes_order_id_foreign');
        });

        Schema::drop('order_notes');
    }
}
