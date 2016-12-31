<?php

namespace hollanbo\Weather\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    public $table = "hollanbo_weather_sensors";

    public $timestamps = true;
    public $guarded = ['id'];

    public function data() {
        return $this->hasMany('hollanbo\Weather\Models\Data');
    }

    public function latestData()
    {
        return $this->data()
            ->join(DB::raw(
                  "(select max(id) id
                    from weather.hollanbo_weather_data
                    group by sensor_id) as t2"
                ), 'hollanbo_weather_data.id', '=', 't2.id');
    }
}
