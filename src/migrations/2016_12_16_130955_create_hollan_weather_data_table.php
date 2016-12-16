<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHollanWeatherDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hollan_weather_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('temperature')->nullable();
            $table->string('humidity')->nullable();
            $table->string('pressure')->nullable();
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
        Schema::drop('hollan_weather_data');
    }
}
