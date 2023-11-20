<?php

namespace App\Controller;

use App\Services\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController {
    #[Route('/', methods: ['GET', 'POST'])]
    public function hello(): Response
    {
        return $this->render('default.html.twig');
    }

    #[Route('/hello/all')]
    function helloAll(): Response
    {
        return new Response('Hello Everyone !');
    }

    #[Route('/hello/{name}', requirements: ['name' => '\p{L}+'])]
    function sayHello(string $name): Response
    {
        return new Response('Hello ' . $name . ' !');
    }

    function menu(Menu $menu): Response
    {
        return $this->render('Fragments/_menu.html.twig', [
            'items' => $menu->getMenu()
        ]);
    }
}