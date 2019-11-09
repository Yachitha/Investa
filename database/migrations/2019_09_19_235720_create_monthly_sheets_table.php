<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlySheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->float('loan_payment');
            $table->float('e_income');
            $table->float('sup_cash_loan');
            $table->float('sup_che_loan');
            $table->float('income');
            $table->float('loan_cash');
            $table->float('loan_che');
            $table->float('salary_payment');
            $table->float('sup_loan_cash_pay');
            $table->float('sup_loan_che_pay');
            $table->float('ex_expenses');
            $table->float('expenses');
            $table->float('monthly_income');
            $table->float('monthly_deposit');
            $table->float('monthly_profit');
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
        Schema::dropIfExists('monthly_sheets');
    }
}
