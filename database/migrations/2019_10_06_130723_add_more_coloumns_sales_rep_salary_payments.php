<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColoumnsSalesRepSalaryPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_rep_salary_payments', function (Blueprint $table) {
            $table->integer('cash_book_id')->nullable();
            $table->integer('bank_book_id')->nullable();
            $table->string('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_rep_salary_payments', function (Blueprint $table) {
            //
        });
    }
}
