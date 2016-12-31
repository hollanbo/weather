<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHollanboWeatherSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hollanbo_weather_sensors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('channel');
            $table->string('station_mac');
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
        Schema::drop('hollanbo_weather_sensors');
    }
}
