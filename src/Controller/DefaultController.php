<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController
{
    #[Route('/', methods: ['GET'])]
    function index (): Response {
        return new Response('Hello World !');
    }

    #[Route('/random')]
    function random (): Response {
        return new Response(rand(0, 100));
    }

    #[Route('/display/{value}', requirements: ['value' => '^[A-z]+$'])]
    function display ($value = 'Hello World !'): Response {
        return new Response($value);
    }

    #[Route('/display/bienvenue', priority: 1)]
    function displayBienvenue (): Response {
        return new Response('<h1>Bienvenue</h1>');
    }
}