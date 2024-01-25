<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    function index (): Response {
        return $this->render('default.html.twig', [
            'text' => '<h1>Hello World !</h1>'
        ]);
    }

    #[Route('/random')]
    function random (): Response
    {
        return $this->render('default.html.twig', [
            'text' => '<h2>Random</h2><p>' . rand(0, 100) . '</p>'
        ]);
    }

    #[Route('/display/{value}', requirements: ['value' => '^[A-z]+$'])]
    function display ($value = 'Hello World !'): Response
    {
        return $this->render('default.html.twig', [
            'text' => $value
        ]);
    }

    #[Route('/display/bienvenue', priority: 1)]
    function displayBienvenue (): Response
    {
        return $this->render('default.html.twig', [
            'text' => '<h1>Bienvenue</h1>'
        ]);
    }
}