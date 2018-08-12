<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerLoanRepaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_loan_repayment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer ('loan_id');
            $table->integer ('bank_book_id');
            $table->integer ('cash_book_id');
            $table->float ('amount');
            $table->integer ('installment_count');
            $table->float ('remaining_amount');
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
        Schema::dropIfExists('customer_loan_repayment');
    }
}
