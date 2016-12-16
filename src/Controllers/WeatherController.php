<?php
namespace hollanbo\Weather\Controllers;

use App\Http\Controllers\Controller;
use hollanbo\Weather\Repositories\WeatherRepository;

class WeatherController extends Controller
{

    public function getData(WeatherRepository $repo)
    {
        $data = $repo->readFromStation();
        $repo->saveData($data);
        dd('ok', $data);
    }

}
