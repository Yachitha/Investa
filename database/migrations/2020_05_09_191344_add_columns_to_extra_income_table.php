<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToExtraIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extra_incomes', function (Blueprint $table) {
            $table->date ('transaction_date');
            $table->string ('description');
            $table->float ('amount');
            $table->integer ('user_id'); //admin or salesRep
            $table->integer ('cash_book_id')->nullable();
            $table->integer ('bank_book_id')->nullable();
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
        Schema::table('extra_incomes', function (Blueprint $table) {
            //
        });
    }
}
