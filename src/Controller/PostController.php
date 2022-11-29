<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/create', methods: ['GET', 'HEAD'])]
    public function create (): Response
    {
        return $this->render("post/create.html.twig");
    }

    #[Route('/post/create', methods: ['POST'])]
    public function store (Request $request, ManagerRegistry $doctrine): Response
    {
        $post = new Post();
        $post->setBody($request->get('body'));
        $post->setSubject($request->get('subject'));

        $em = $doctrine->getManager();

        $em->persist($post);
        $em->flush();

        return new RedirectResponse("/post/create");
    }

    #[Route('/post/list')]
    public function list (PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        return $this->render("post/list.html.twig", ['posts' => $posts]);
    }

    #[Route('/post/{post}', methods: ['POST'])]
    public function delete (Post $post, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($post);
        $em->flush();
        return new RedirectResponse('/post/list');
    }

    #[Route('/post/{post}', methods: ['GET', 'HEAD'])]
    public function show (Post $post): Response
    {
        return $this->render("post/show.html.twig", ['post' => $post]);
    }

    #[Route('/post/edit/{post}', methods: ['GET', 'HEAD'])]
    public function edit (Post $post): Response
    {
        return $this->render("post/edit.html.twig", ['post' => $post]);
    }

    #[Route('/post/edit/{post}', methods: ['POST'])]
    public function update (
        Post $post,
        Request $request,
        ManagerRegistry $doctrine
    ): Response
    {
        $post->setBody($request->get('body'));
        $post->setSubject($request->get('subject'));

        $em = $doctrine->getManager();

        $em->flush();

        return new RedirectResponse("/post/list");
    }
}