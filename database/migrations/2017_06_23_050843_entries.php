<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Entries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->increments('id')->comment('Entry ID');
            $table->integer('user_id')->index()->comment('User ID');
            $table->integer('from')->comment('From location ID');
            $table->integer('to')->comment('To location ID');
            $table->float('distance')->comment('Miles traveled');
            $table->integer('time')->comment('Time traveled');
            $table->integer('mpg')->nullable()->comment('Miles per gallon during trip');
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
        Schema::dropIfExists('entries');
    }
}
