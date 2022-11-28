<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    #[Route('/default/random')]
    public function random(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Random number: ' . $number . '</body></html>'
        );
    }
}