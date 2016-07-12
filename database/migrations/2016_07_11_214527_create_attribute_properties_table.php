<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttributePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_properties', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_attribute_id');
            $table->string('slug');
            $table->string('name');
            $table->integer('order')->default(0);
            $table->unique(['slug', 'product_attribute_id']);
            $table->timestamps();
        });

        Schema::table('attribute_properties', function ($table) {
            $table->foreign('product_attribute_id')->references('id')->on('product_attributes')->onDelete('cascade');
        });

        /*
         * The join table to products
         */
        Schema::create('attribute_property_product', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
            $table->integer('attribute_property_id')->unsigned();
        });

        Schema::table('attribute_property_product', function ($table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attribute_properties', function (Blueprint $table) {
            $table->dropForeign('attribute_properties_product_attribute_id_foreign');
        });

        Schema::table('attribute_property_product', function ($table) {
            $table->dropForeign('attribute_property_product_product_id_foreign');
        });

        Schema::drop('attribute_properties');
        Schema::drop('attribute_property_product');
    }
}
