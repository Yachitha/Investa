<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_loan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer ('supplier_id');
            $table->date('date')->nullable();
            $table->float ('amount');
            $table->float ('interest_rate')->nullable();
            $table->float ('installment_amount')->nullable();
            $table->integer ('no_of_installments')->nullable();
            $table->date ('start_date')->nullable();
            $table->date ('end_date')->nullable();
            $table->date('finished_date')->nullable();
            $table->boolean('isFinished')->nullable();
            $table->float('due_amount')->nullable();
            $table->string('type');
            $table->string('bank')->nullable();
            $table->date('banking_date')->nullable();
            $table->integer('cheque_no')->nullable();
            $table->integer('cash_book_id')->nullable();
            $table->integer('bank_book_id')->nullable();
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
        Schema::dropIfExists('supplier_loan');
    }
}
