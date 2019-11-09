<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaySheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->float('bf_amount');
            $table->integer('route_id');
            $table->float('loan_payment');
            $table->float('e_income');
            $table->float('sup_cash_loan');
            $table->float('sup_che_loan');
            $table->float('emp_loan_payment');
            $table->float('access');
            $table->float('income');
            $table->float('loan_cash');
            $table->float('loan_che');
            $table->float('salary_payment');
            $table->float('sup_loan_cash_pay');
            $table->float('sup_loan_che_pay');
            $table->float('emp_loan');
            $table->float('ex_expenses');
            $table->float('expenses');
            $table->float('total_income');
            $table->float('bank_deposit');
            $table->float('day_cash');
            $table->float('cash_out');
            $table->float('balance');
            $table->float('cash_in_locker');
            $table->float('day_profit');
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
        Schema::dropIfExists('day_sheets');
    }
}
