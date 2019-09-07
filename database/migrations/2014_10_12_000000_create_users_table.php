<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->string('email')->unique();
            $table->string('password');
            $table->string ('profile_pic');
            $table->string ('NIC');
            $table->string ('addLine1');
            $table->string ('addLine2');
            $table->string ('city');
            $table->integer ('role_id');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer ('route_id')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
