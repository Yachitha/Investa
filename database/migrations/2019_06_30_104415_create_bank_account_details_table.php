<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_account_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_no');
            $table->string('bank_name');
            $table->integer('balance');
            $table->integer('total_cash_in');
            $table->integer('total_cash_out');
            $table->string('example_col_1');
            $table->string('example_col_2');
            $table->string('example_col_3');
            $table->string('example_col_4');
            $table->string('example_col_5');
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
        Schema::dropIfExists('bank_account_details');
    }
}
