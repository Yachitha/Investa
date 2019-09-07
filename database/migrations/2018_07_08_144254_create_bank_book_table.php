<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_book', function (Blueprint $table) {
            $table->increments('id');
            $table->date ('transaction_date');
            $table->string ('description');
            $table->float ('deposit');
            $table->float ('withdraw');
            $table->float ('balance');
            $table->string ('cheque_no')->nullable();
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
        Schema::dropIfExists('bank_book');
    }
}
