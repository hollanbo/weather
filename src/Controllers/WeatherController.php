<?php
namespace hollanbo\Weather\Controllers;

use App\Http\Controllers\Controller;
use hollanbo\Weather\Repositories\WeatherRepository;

class WeatherController extends Controller
{

    /**
     * Get data from station, save it and display it as a dump.
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
        $data = $repo->readFromStation();
        $repo->saveData($data);

        dd($data);
    }

}
