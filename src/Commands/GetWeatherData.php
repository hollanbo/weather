<?php

namespace hollanbo\Weather\Commands;

use Illuminate\Console\Command;
use hollanbo\Weather\Repositories\WeatherRepository;

class GetWeatherData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hollanbo:getWeatherData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data and save it to database. Should be run by Cron at desired intervals.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(WeatherRepository $repo)
    {
        $data = $repo->readFromStation();
        $repo->saveDataBatch($data);
    }
}
