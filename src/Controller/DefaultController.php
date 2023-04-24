<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    function number (): Response
    {
        $number = random_int(0, 100);
        return new Response($number);
    }
}