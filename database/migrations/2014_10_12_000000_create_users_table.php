<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->increments('id')->comment('User ID');
            $table->string('name')->comment('User name');
            $table->string('email')->unique()->comment('User email');
            $table->string('password')->comment('Hashed user email');
            $table->timestamp('last_report')->comment('Last time user received report')->nullable();
            $table->integer('report_schedule')->comment('Days between reports');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
