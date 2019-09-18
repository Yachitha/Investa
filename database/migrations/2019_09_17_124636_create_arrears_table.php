<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArrearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrears', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('loan_id');
            $table->integer('customer_id');
            $table->integer('route_id');
            $table->integer('sales_rep_id');
            $table->float('amount');
            $table->date('date');
            $table->integer('cash_book_id')->nullable();
            $table->integer('bank_book_id')->nullable();
            $table->string('extra_str_1')->nullable();
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
        Schema::dropIfExists('arrears');
    }
}
