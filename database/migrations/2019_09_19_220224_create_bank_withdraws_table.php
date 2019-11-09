<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_withdraws', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('acc_id');
            $table->integer('with_des');
            $table->string('type')->nullable();
            $table->float('amount');
            $table->integer('cheque_no')->nullable();
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
        Schema::dropIfExists('bank_withdraws');
    }
}
