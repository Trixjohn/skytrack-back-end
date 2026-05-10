<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\WeatherLog;
use Illuminate\Http\Request; // <--- ADD THIS LINE

class WeatherController extends Controller
{
    public function getWeather($city)
    {
        $apiKey = "2e305bbe2644c72fc398c999d2937606";
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric'
        ]);

        $data = $response->json();

        WeatherLog::create([
            'city' => $city,
            'temperature' => $data['main']['temp'] ?? 0,
            'condition' => $data['weather'][0]['description'] ?? 'unknown'
        ]);

        return response()->json([
            'city' => $city,
            'temperature' => $data['main']['temp'] ?? null,
            'condition' => $data['weather'][0]['description'] ?? null
        ]);
    }

    public function getAllWeather() {
        return response()->json(WeatherLog::all());
    }

    public function addWeather(Request $request) {
        $log = WeatherLog::create($request->all());
        return response()->json(['message' => 'Weather log added!', 'data' => $log], 201);
    }

    public function updateWeather(Request $request, $id) {
        $log = WeatherLog::find($id);
        if($log) {
            $log->update($request->all());
            return response()->json(['message' => 'Updated successfully!', 'data' => $log]);
        }
        return response()->json(['message' => 'Log not found'], 404);
    }

    public function deleteWeather($id) {
        $log = WeatherLog::find($id);
        if($log) {
            $log->delete();
            return response()->json(['message' => 'Log deleted!']);
        }
        return response()->json(['message' => 'Log not found'], 404);
    }
} // This is the final bracket. Nothing should be below this!