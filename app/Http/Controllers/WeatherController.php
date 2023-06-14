<?php

namespace App\Http\Controllers;

use App\Services\GoWeatherService;
use App\Services\WeatherStackService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class WeatherController extends Controller
{
    use ApiResponser;

    public function weather(Request $request)
    {
        $validator = $this->validateCity();
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $goWeatherTemperature = (new GoWeatherService())->getTemperature($request->city);
        $weatherStackTemperature = (new WeatherStackService())->getTemperature($request->city);

        if ($goWeatherTemperature !== null && $weatherStackTemperature !== null) {
            $avgTemperature = ($goWeatherTemperature + $weatherStackTemperature) / 2;
        } elseif ($goWeatherTemperature !== null) {
            $avgTemperature = $goWeatherTemperature;
        } else {
            $avgTemperature = $weatherStackTemperature;
        }

        return $this->successResponse([
            'goWeatherTemperature' => $goWeatherTemperature,
            'weatherStackTemperature' => $weatherStackTemperature,
            'avgTemperature' => $avgTemperature,
        ], '', 200);
    }

    public function validateCity(): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make(request()->all(), [
            'city' => 'required|string|max:255',
        ]);
    }
}
