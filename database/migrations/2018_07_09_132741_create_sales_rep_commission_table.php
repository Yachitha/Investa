<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesRepCommissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_rep_commission', function (Blueprint $table) {
            $table->increments('id');
            $table->float ('commission_rate');
            $table->float ('commission_amount');
            $table->date('date');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer ('cash_book_id')->nullable();
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer ('bank_book_id')->nullable();
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
        Schema::dropIfExists('sales_rep_commission');
    }
}
