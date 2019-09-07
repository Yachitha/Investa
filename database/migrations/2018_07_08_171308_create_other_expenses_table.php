<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtherExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->date ('transaction_date');
            $table->string ('description');
            $table->float ('amount');
            $table->integer ('user_id'); //admin or salesRep
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
        Schema::dropIfExists('other_expenses');
    }
}
