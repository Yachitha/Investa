<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_loan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer ('loan_no');
            $table->float ('interest_rate');
            $table->float ('loan_amount');
            $table->float ('total_loan_amount');
            $table->float ('installment_amount');
            $table->integer ('no_of_installments');
            $table->date ('start_date');
            $table->date ('end_date');
            $table->integer ('duration');
            $table->integer ('customer_id');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer ('cash_book_id')->nullable();
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer ('bank_book_id')->nullable();
            $table->boolean('isFinished');
            $table->boolean('isArrears');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->string('description')->nullable();
            $table->float('due_amount');
            $table->float('arrears_amount');
            $table->string('type');
            $table->integer('cheque_no');
            $table->integer('bank_acc_id')->nullable();
            $table->boolean('arrears_next');
            $table->date('finished_date')->nullable();
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
        Schema::dropIfExists('customer_loan');
    }
}
