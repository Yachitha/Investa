<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesBFsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes_b_fs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('route_id');
            $table->date('date')->nullable();
            $table->float('amount');
            $table->string('status')->nullable();
            $table->float('acc');
            $table->integer('user_id');
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
        Schema::dropIfExists('routes_b_fs');
    }
}
