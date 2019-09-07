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
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer ('bank_book_id')->nullable();
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer ('cash_book_id')->nullable();
            $table->float ('amount');
            $table->integer ('installment_count');
            $table->float ('remaining_amount');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->boolean ('isChecked')->default(0);
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
        Schema::dropIfExists('customer_loan_repayment');
    }
}
