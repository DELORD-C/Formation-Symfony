<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/')]
    function home (): Response
    {
        return $this->render('default/home.html.twig', [
            'html' => '<p>YOLO</p>'
        ]);
    }

    #[Route(
        '/number/{min}/{max}',
        requirements: [
            'min' => '\d+', 'max' => '\d+'
        ],
        name: "number"
    )]
    function number (int $min = 0, int $max = 100): Response
    {
        $number = random_int($min, $max);
        return $this->render('default/variable.html.twig', [
            'variable' => $number
        ]);
    }

    #[Route('/number/fake')]
    function numberFake (): Response
    {
        return new Response(10000);
    }
}