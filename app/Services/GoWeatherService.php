<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoWeatherService
{
    protected $baseUrl;

    public function __construct() {
       $this->baseUrl = config('services.weather_api.go_weather.url');
    }

    public function getTemperature(string $city)
    {
        $response = Http::baseUrl(
            $this->baseUrl
        )->get("/weather/{$city}")->json();

        return !empty($response['temperature']) ?
            intval($response['temperature']) :
            null;
    }
}
