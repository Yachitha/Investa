<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesRepLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_rep_loan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer ('loan_no');
            $table->float ('loan_amount');
            $table->float ('installment_amount');
            $table->float ('interest_rate');
            $table->date ('start_date');
            $table->date ('end_date');
            $table->integer ('duration');
            $table->integer ('sales_rep_id');
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
        Schema::dropIfExists('sales_rep_loan');
    }
}
