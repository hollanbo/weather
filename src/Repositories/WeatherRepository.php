<?php
namespace hollanbo\Weather\Repositories;

use Carbon\Carbon;
use App;
use hollanbo\Weather\Models\Data;

class WeatherRepository {

    public function readFromStation()
    {
        $path = base_path() . '/python';

        $command = 'python ' . $path . '/bleWeatherStation.py F5:AC:BC:3B:90:48 2>&1';

        $out = [];
        exec($command, $out);

        $weather_data = [];
        $columns = [
            'temperature',
            'humidity',
            'pressure',
        ];

        $timestamp = new Carbon();

        foreach ($columns as $column) {
            $default[$column] = NULL;
        }

        foreach ($out as $unit => $row) {
            $data = explode(' , ', $row);

            $sensor_data = $default;

            foreach ($data as $key => $value) {
                if ($value > 100) {
                    break;
                }

                $column = $columns[$key];
                if ($column === 'humidity') {
                    $value = $value / 100;
                }

                $sensor_data[$column] = (float) $value;
            }

            if ($sensor_data == $default) {
                continue;
            }

            $sensor_data['created_at'] = $timestamp;
            $sensor_data['updated_at'] = $timestamp;
            $weather_data[$unit] = $sensor_data;

        }

        dd($weather_data);
        return $weather_data;
    }

    public function saveData(array $data)
    {
        $model = resolve('hollanbo\Weather\Models\Data');
        $model->insert($data);
    }
}
