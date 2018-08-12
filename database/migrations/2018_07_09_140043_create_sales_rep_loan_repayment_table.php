<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesRepLoanRepaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_rep_loan_repayment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer ('loan_id');
            $table->float ('amount');
            $table->integer ('installment_count');
            $table->float ('remaining_amount');
            $table->integer ('cash_book_id');
            $table->integer ('bank_book_id');
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
        Schema::dropIfExists('sales_rep_loan_repayment');
    }
}
