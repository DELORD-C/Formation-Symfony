<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class DefaultController extends AbstractController
{
    #[Route('/default/{min}/{max<\d+>}', priority: 1)]
    public function randomMinMax($min, int $max = 100): Response
    {
        if (!is_int($min))
            $min = 0;

        $number = random_int($min, $max);
        return $this->render('default/random.html.twig', ['random' => $number]);
    }

    #[Route('/')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
}