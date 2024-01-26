<?php

namespace App\Service;


readonly class WeatherApi
{
    function getCurrent(string $lat, string $lon): mixed
    {
        $json = $this->getCurrentRaw($lat, $lon);
        return json_decode($json);
    }

    function getCurrentRaw(string $lat, string $lon): string
    {
        return file_get_contents("https://api.open-meteo.com/v1/forecast?latitude=$lat&longitude=$lon&current=temperature_2m");
    }

    function getDaily(string $lat, string $lon): mixed
    {
        $json = file_get_contents("https://api.open-meteo.com/v1/forecast?latitude=$lat&longitude=$lon&daily=temperature_2m_max,temperature_2m_min");
        return json_decode($json);
    }
}