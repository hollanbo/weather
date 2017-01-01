<?php
namespace hollanbo\Weather\Controllers;

use App\Http\Controllers\Controller;
use hollanbo\Weather\Repositories\WeatherRepository;

class WeatherController extends Controller
{

    /**
     * Get data from station, save it and display it.
     *
     * @param   WeatherRepository $repo dependency
     *
     * @return  dump
     *
     * @author Borut Hollan <borut.hollan@gmail.com>
     *
     * @version 1.0
     */
    public function getFreshData(WeatherRepository $repo)
    {
        $data = $repo->readFromStation();
        $repo->saveDataBatch($data);

        return redirect()->route('hollanbo.weather.getData');
    }

    /**
     * Get data from database and display it.
     *
     * @param   WeatherRepository $repo dependency
     *
     * @return  dump
     *
     * @author Borut Hollan <borut.hollan@gmail.com>
     *
     * @version 1.0
     */
    public function getData(WeatherRepository $repo)
    {
        $sensors_data = $repo->getSensorsData();

        return view('hollanbo_weather::sensors_data', $sensors_data);
    }

}
