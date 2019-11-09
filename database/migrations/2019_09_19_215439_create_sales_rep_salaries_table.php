<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesRepSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_rep_salaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_rep_id');
            $table->float('basic');
            $table->float('add')->nullable();
            $table->string('description')->nullable();
            $table->date('month')->nulable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_rep_salaries');
    }
}
