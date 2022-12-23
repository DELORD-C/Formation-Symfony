<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Cache(maxage: 600, public: true, mustRevalidate: true)]
    #[Route('/')]
    public function home(): Response
    {
        return $this->render('default.html.twig', [
            'content' => 'Hello World !',
            'title' => 'Home'
        ]);
    }

    #[Route(
        '/random/{min}/{max}',
        requirements: ['min' => '\d+|default', 'max' => '\d+|inf'],
        methods: ['GET', 'HEAD']
    )]
    public function random($min = 0, $max = 1000): Response
    {
        if ($min == "default")
            $min = 0;

        if ($max == "inf")
            $max = 999999999999999;

        return $this->render('default.html.twig', [
            'content' => random_int($min, $max),
            'title' => 'Random Number'
        ]);
    }

    #[Route('/locale/{locale}')]
    public function locale (string $locale, Request $request)
    {
        //si la locale est prise en charge
        if (in_array($locale, ['fr', 'en', 'es'])) {
            //on la stocke dans la session
            $request->getSession()->set('_locale', $locale);
        }
        // puis on redirige vers la page précédente
        return $this->redirect($request->headers->get('referer'));;
    }
}