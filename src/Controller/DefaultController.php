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
        requirements: ['min' => '\d+|default', 'max' => '\d+|inf'],
        methods: ['GET', 'HEAD']
    )]
    public function random(int $min = 0, int $max = 1000): Response
    {
        if ($min = "default")
            $min = 0;

        if ($max = "inf")
            $max = 999999999999999;

        return new Response(
            random_int($min, $max)
        );
    }
}