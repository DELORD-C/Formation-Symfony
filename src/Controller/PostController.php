<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/create', methods: ['GET', 'HEAD'])]
    public function create (): Response
    {
        return $this->render('post/create.html.twig');
    }

    #[Route('/post/create', methods: ['POST'])]
    public function store (Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $post = new Post();
        $post->setSubject($request->get('subject'));
        $post->setBody($request->get('body'));
        $entityManager->persist($post);
        $entityManager->flush();

        return new RedirectResponse('/post/create');
    }
}