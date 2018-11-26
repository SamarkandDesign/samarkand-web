<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddMetaDescFieldToPagesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('pages', function ($table) {
      $table
        ->string('meta_description')
        ->after('slug')
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
    Schema::table('pages', function ($table) {
      $table->dropColumn('meta_description');
    });
  }
}
