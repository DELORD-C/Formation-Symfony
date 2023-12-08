<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultController extends AbstractController {
    #[Route('/')]
    public function index (TranslatorInterface $trans): Response
    {
        return $this->render('default.html.twig', [
            'text' => $trans->trans('home.message'),
            'title' => 'Index'
        ]);
    }

    #[Route('/hello/all')]
    public function helloAll (): Response
    {
        return $this->render('default.html.twig', [
            'text' => 'Hello Everybody !',
            'title' => 'Greetings'
        ]);
    }

    #[Route('/hello/{name}', requirements: ['name' => '\p{L}+'])]
    public function hello (string $name): Response
    {
        return $this->render('default.html.twig', [
            'text' => "Hello $name !",
            'title' => 'Greetings'
        ]);
    }

    #[Route('/random')]
    public function random (): Response
    {
        return $this->render('default.html.twig', [
            'text' => rand(1, 1000),
            'title' => 'Random'
        ]);
    }

    #[Route('/random/{max}', requirements: ['max' => '[0-9]+'])]
    public function randomMax (int $max): Response
    {
        return $this->render('default.html.twig', [
            'text' => rand(1, $max),
            'title' => 'Random Max'
        ]);
    }

    #[Route('/random/{min}/{max}', requirements: ['min' => '[0-9]+', 'max' => '[0-9]+'])]
    public function randomMinMax (int $min, int $max): Response
    {
        return $this->render('default.html.twig', [
            'text' => rand($min, $max),
            'title' => 'Random Min Max'
        ]);
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