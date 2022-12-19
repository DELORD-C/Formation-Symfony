<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/')]
    public function home(): Response
    {
        return new Response(
            'Hello World !'
        );
    }

    #[Route(
        '/random/{min}/{max}',
        requirements: ['min' => '\d+', 'max' => '\d+'],
        methods: ['GET', 'HEAD']
    )]
    public function random(int $min = 0, int $max = 1000): Response
    {
        return new Response(
            random_int($min, $max)
        );
    }

    #[Route(
        '/random/999/{min}',
        requirements: ['min' => '\d+'],
        methods: ['GET', 'HEAD'],
        priority: 1
    )]
    public function randomInf(int $min): Response
    {
        return new Response(
            random_int($min, 9999999999999999999999999999999999999999999)
        );
    }
}