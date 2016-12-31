<?php
namespace hollanbo\Weather\Repositories;

use Carbon\Carbon;
use App;
use hollanbo\Weather\Models\Data;
use hollanbo\Weather\Models\Sensor;
use Illuminate\Support\Facades\DB;

class WeatherRepository {

    /**
     * Read data from weather station.
     *
     * @return  array   Formatted data with timestamps
     *
     * @author Borut Hollan <borut.hollan@gmail.com>
     *
     * @version 1.0
     */
    public function readFromStation($station_mac = "F5:AC:BC:3B:90:48")
    {
        $path = __DIR__ . '/../python';

        $command = "python " . $path . "/oregon_scientific_ble.py $station_mac 2>&1";

        $out = [];
        exec($command, $out);

        $weather_data = [];
        $columns = [
            'channel',
            'temperature',
            'humidity',
            'pressure',
        ];

        $timestamp = new Carbon();

        foreach ($out as $unit => $row) {
            $data = explode(' , ', $row);
            $sensor_data = [];
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

            // If all sensor data is null, skip
            if (!isset($sensor_data['temperature'])
                && !isset($sensor_data['humidity'])
                && !isset($sensor_data['pressure']) ) {
                continue;
            }

            $sensor_data['station_mac'] = $station_mac;
            $sensor_data['created_at'] = $timestamp;
            $sensor_data['updated_at'] = $timestamp;
            $weather_data[$unit] = $sensor_data;

        }

        return $weather_data;
    }

    public function saveDataBatch(array $data)
    {
        foreach ($data as $sensor_data) {
            $this->saveData($sensor_data);
        }
    }

    /**
     * Save Weather station data to database.
     *
     * @param   array       $data weather station data
     *                      [
     *                          "channel",
     *                          "temperature",
     *                          "humidity",
     *                          "pressure",
     *                          "station_mac",
     *                          "created_at",
     *                          "updated_at"
     *                      ]
     *
     * @return  void
     *
     * @author Borut Hollan <borut.hollan@gmail.com>
     *
     * @version 1.1
     */
    public function saveData(array $data)
    {
        $sensor = $this->getSensor($data['channel'], $data['station_mac']);

        unset($data['channel']);
        unset($data['station_mac']);

        return $sensor->data()->create($data);
    }

    public function getSensor($channel, $station_mac)
    {
        return Sensor::firstOrCreate([
            'channel' => $channel,
            'station_mac' => $station_mac
        ], [
            'name' => $channel
        ]);
    }
    public function getLatestData()
    {
        return Data::select(DB::raw('hollanbo_weather_data.*'))
            ->join(DB::raw(
                  "(select max(id) id
                    from weather.hollanbo_weather_data
                    group by sensor_id) as t2"
                ), 'hollanbo_weather_data.id', '=', 't2.id')
            ->get();
    }

    public function getSensorsData()
    {
        $sensors = Sensor::all();
        $data = $this->getLatestData()->keyBy('sensor_id');

        return compact('sensors', 'data');
    }
}
