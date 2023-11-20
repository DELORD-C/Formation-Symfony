<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController {
    #[Route('/', methods: ['GET', 'POST'])]
    public function hello(): Response
    {
        return new Response('Hello World !');
    }
}