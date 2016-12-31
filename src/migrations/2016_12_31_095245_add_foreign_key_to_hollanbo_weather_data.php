<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToHollanboWeatherData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('hollanbo_weather_data', function (Blueprint $table) {
             $table->foreign('sensor_id')
                ->references('id')->on('hollanbo_weather_sensors')
                ->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hollanbo_weather_data', function (Blueprint $table) {
            $table->dropForeign('hollanbo_weather_data_sensor_id_foreign');
        });
    }
}
