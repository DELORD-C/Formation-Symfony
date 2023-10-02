<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

//    #[Route('/', name: "index")]
    #[Route('/')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/hello/{it}', requirements: ['it' => '\d+'])]
//    #[Route('/hello/{it<\d+>}')]
    public function hello(int $it): Response
    {
        return $this->render('default.html.twig', [
            'it' => $it
        ]);
    }

    #[Route('/hello/1', priority: 1)]
    public function helloSingle(): Response
    {
        return new Response('HELLO WORLD !');
    }

    public function navbar(): Response
    {
        return $this->render('Fragments/_navbar.html.twig');
    }
}