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
        return hollanbo\Weather\Models\Data::select(DB::raw('hollanbo_weather_data.* as t1'))
            ->join(DB::raw(
                  "(select max(id) id
                    from hollanbo_weather_data
                    group by sensor_id) as t2"
                ), 't1.id', '=', 't2.id');
    }
}
