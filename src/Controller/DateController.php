<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DateController extends AbstractController
{
    /**
     * @return Response
     * @Route("/date/day")
     */
    function day(): Response
    {
        return $this->render(
            'display.html.twig',
            [
                'title' => 'Week actual day',
                'data' => date('l')
            ]
        );
    }
}