<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherController;




Route::get('/weather/{city}', [WeatherController::class, 'getWeather']);
Route::get('/weather', [WeatherController::class, 'getAllWeather']);
Route::post('/weather', [WeatherController::class, 'addWeather']);
Route::put('/weather/{id}', [WeatherController::class, 'updateWeather']);
Route::delete('/weather/{id}', [WeatherController::class, 'deleteWeather']);

