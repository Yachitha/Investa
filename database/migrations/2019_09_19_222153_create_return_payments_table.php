<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('return_cheque_id');
            $table->date('date');
            $table->string('type')->nullable();
            $table->float('amount');
            $table->integer('cheque_id');
            $table->integer('acc_id')->nullable();
            $table->date('banking_date')->nullable();
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
        Schema::dropIfExists('return_payments');
    }
}
