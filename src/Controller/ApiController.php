<?php

namespace App\Controller;

use App\Service\WeatherApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/weather/{lat}/{lon}')]
    function weather (WeatherApi $api, string $lat = '48.85', string $lon = '2.29'): Response
    {
        $response = new Response($api->getCurrentRaw($lat, $lon));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    #[Route('/weatherPost')]
    function weatherPost (WeatherApi $api, Request $request): Response
    {
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');
        $response = new Response($api->getCurrentRaw($lat, $lon));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}