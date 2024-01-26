<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

readonly class WeatherApi
{
    function __construct(private LoggerInterface $logger) {}
    function getCurrent(): mixed
    {
        $json = file_get_contents("https://api.open-meteo.com/v1/forecast?latitude=45.73&longitude=4.85&current=temperature_2m");
        $this->logger->info($json);
        return json_decode($json);
    }

    function getDaily(): mixed
    {
        $json = file_get_contents("https://api.open-meteo.com/v1/forecast?latitude=45.73&longitude=4.85&daily=temperature_2m_max,temperature_2m_min");
        $this->logger->info($json);
        return json_decode($json);
    }
}