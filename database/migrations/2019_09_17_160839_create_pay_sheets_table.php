<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaySheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_rep_id');
            $table->date('month');
            $table->float('basic_salary')->nullable();
            $table->float('total');
            $table->date('start');
            $table->date('end');
            $table->float('commission');
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
        Schema::dropIfExists('pay_sheets');
    }
}
