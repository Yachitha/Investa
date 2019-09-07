<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtraDetails3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_details_3', function (Blueprint $table) {
            $table->increments('id');
            $table->string('example_col_1');
            $table->string('example_col_2');
            $table->string('example_col_3');
            $table->string('example_col_4');
            $table->string('example_col_5');
            $table->string('example_col_6');
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
        Schema::dropIfExists('extra_details_3');
    }
}
