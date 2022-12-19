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

    #[Route('/random/{min}/{max}', requirements: ['min' => '\d+', 'max' => '\d+'])]
    public function random(int $min, int $max): Response
    {
        return new Response(
            random_int($min, $max)
        );
    }
}