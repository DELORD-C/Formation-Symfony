<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class DefaultController
{
    #[Route('/default/{min}/{max<\d+>}', priority: 1)]
    public function randomMinMax($min, int $max = 100): Response
    {
        if (!is_int($min))
            $min = 0;

        $number = random_int($min, $max);
        return new Response(
            '<html><body>Random number: ' . $number . '</body></html>'
        );
    }
}