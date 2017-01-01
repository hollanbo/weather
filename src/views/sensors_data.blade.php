@extends('hollanbo_weather::master')

@section('content')
    <div class="col-xs-12 refresh">
        <a href="{{ route('hollanbo.weather.fresh') }}" class="glyphicon glyphicon-refresh" aria-hidden="true"></a>
    </div>
    @foreach ($sensors as $key => $sensor)
        <div class="sensor-container col-xs-6 col-md-4 col-lg-3">
            <div class="sensor-name">
                <span class="title">Sensor</span>
                {{ $sensor->name }}
            </div>
            <div class="sensor-data">
                <div>
                    @if (isset($data[$sensor->id]->temperature))
                        <span class="title">Temperature</span>
                        {{ $data[$sensor->id]->temperature }} °C
                    @endif
                </div>
                <div>
                    @if (isset($data[$sensor->id]->humidity))
                        <span class="title">Humidity</span>
                        {{ $data[$sensor->id]->humidity }}%
                    @endif
                </div>
                <div>
                    @if (isset($data[$sensor->id]->pressure))
                        <span class="title">Pressure</span>
                        {{ $data[$sensor->id]->pressure }} Pa
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@stop
