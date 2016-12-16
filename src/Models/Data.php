<?php

namespace hollan\Weather\Models;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $table = "hollan_weather_data";

    public $timestamps = true;
}
