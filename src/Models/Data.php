<?php

namespace hollanbo\Weather\Models;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    public $table = "hollanbo_weather_data";

    public $timestamps = true;
    public $guarded = ['id'];

    public function sensor()
    {
        return $this->belongsTo('hollanbo\Weather\Models\Sensor');
    }

    public function getHumidityPercentAttribute()
    {
        return $this->humidity * 100;
    }

    public function getCreatedTimeAttribute()
    {
        return $this->created_at->format('H:i');
    }
}
