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
            $table->integer ('loan_no');
            $table->float ('loan_amount');
            $table->float ('interest_rate');
            $table->float ('installment_amount');
            $table->integer ('no_of_installments');
            $table->date ('start_date');
            $table->date ('end_date');
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
        Schema::dropIfExists('supplier_loan');
    }
}
