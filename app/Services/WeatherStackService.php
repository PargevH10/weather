<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherStackService
{

    protected $baseUrl;
    protected $apiToken;

    public function __construct()
    {
       $this->baseUrl = config('services.weather_api.weather_stack.url');
       $this->apiToken = config('services.weather_api.weather_stack.key');
    }

    public function getTemperature(string $city)
    {
        $response = Http::baseUrl($this->baseUrl)
            ->get("current?access_key={$this->apiToken}&query={$city}")
            ->json();
        return $response['current']['temperature'] ?? null;
    }
}
