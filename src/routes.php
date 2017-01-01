<?php

Route::group(['prefix' => 'hollanbo/weather'], function () {
    Route::get('/', 'hollanbo\Weather\Controllers\WeatherController@getData')->name('hollanbo.weather.getData');
    Route::get('fresh', 'hollanbo\Weather\Controllers\WeatherController@getFreshData')->name('hollanbo.weather.fresh');
});
