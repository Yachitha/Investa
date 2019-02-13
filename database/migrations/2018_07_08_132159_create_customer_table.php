<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer ('customer_no');
            $table->string ('name');
            $table->string ('NIC')->unique();
            $table->string ('contact_no');
            $table->string ('status');
            $table->string ('addLine1');
            $table->string ('addLine2');
            $table->string ('city');
            $table->integer ('salesRep_id');
            $table->integer ('route_id');
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
        Schema::dropIfExists('customer');
    }
}
