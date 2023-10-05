<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultController extends AbstractController
{

//    #[Route('/', name: "index")]
    #[Cache(maxage: 3600, public: true, mustRevalidate: true)]
    #[Route('/')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/hello/{it}', requirements: ['it' => '\d+'])]
//    #[Route('/hello/{it<\d+>}')]
    public function hello(int $it): Response
    {
        return $this->render('default.html.twig', [
            'it' => $it
        ]);
    }

    #[Route('/hello/1', priority: 1)]
    public function helloSingle(TranslatorInterface $translator): Response
    {
        return new Response($translator->trans('home.H1'));
    }

    public function navbar(): Response
    {
        return $this->render('Fragments/_navbar.html.twig');
    }

    #[Route('/locale/{locale}')]
    public function locale (string $locale, Request $request): Response
    {
        $request->getSession()->set('_locale', $locale);
        if ($request->headers->get('referer')) {
            return $this->redirect($request->headers->get('referer'));
        }
        return $this->redirectToRoute('app_default_index');
    }
}