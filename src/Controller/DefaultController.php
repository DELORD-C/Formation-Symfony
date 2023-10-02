<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

//    #[Route('/', name: "index")]
    #[Route('/')]
    public function index(): Response
    {
        return new Response("Hello World !");
    }

    #[Route('/hello/{it}', requirements: ['it' => '\d+'])]
//    #[Route('/hello/{it<\d+>}')]
    public function hello(int $it): Response
    {
        $str = '';
        for ($i = 0; $i < $it; $i++) {
            $str .= '<p>Hello World !</p>';
        }
        return new Response($str);
    }

    #[Route('/hello/1', priority: 1)]
    public function helloSingle(): Response
    {
        return new Response('HELLO WORLD !');
    }
}