<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnedChequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returned_cheques', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('acc_id');
            $table->float('amount');
            $table->string('description')->nullable();
            $table->date('date');
            $table->integer('cheque_no');
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
        Schema::dropIfExists('returned_cheques');
    }
}
