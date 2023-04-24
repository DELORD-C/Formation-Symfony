<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DateController extends AbstractController
{
    #[Route('/date/now')]
    function now (): Response
    {
        return $this->render('default/variable.html.twig', [
            'variable' => date('d-m-Y')
        ]);
    }

    #[Route('/date/jour')]
    function jour (): Response
    {
        $jours = [
            'Lundi',
            'Mardi',
            'Mercredi',
            'Jeudi',
            'Vendredi',
            'Samedi',
            'Dimanche'
        ];

        return $this->render('default/variable.html.twig', [
            'variable' => $jours[array_rand($jours)]
        ]);
    }
}