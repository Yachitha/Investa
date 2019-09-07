<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesRepExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_rep_expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->date ('transaction_date');
            $table->string ('description');
            $table->float ('amount');
            $table->integer ('sales_rep_id');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer ('cash_book_id')->nullable();
            /** @noinspection PhpUndefinedMethodInspection */
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
        Schema::dropIfExists('sales_rep_expenses');
    }
}
