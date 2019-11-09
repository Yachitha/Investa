<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierLoanRepaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_loan_repayment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer ('loan_id');
            $table->date('date');
            $table->float ('amount');
            $table->string('bank')->nullable();
            $table->date('banking_date')->nullable();
            $table->string('type');
            $table->integer('cheque_no')->nullable();
            $table->float ('remaining_amount')->nullable();
            $table->integer ('installment_count')->nullable();
            $table->integer ('cash_book_id')->nullable();
            $table->integer ('bank_book_id')->nullable();
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
        Schema::dropIfExists('supplier_loan_repayment');
    }
}
