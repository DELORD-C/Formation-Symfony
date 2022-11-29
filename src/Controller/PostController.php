<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/create')]
    public function create (Request $request, ManagerRegistry $doctrine): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($post);
            $em->flush();
            return new RedirectResponse("/post/create");
        }

        return $this->renderForm("post/form.html.twig", ['form' => $form, 'label' => 'Create']);
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

    #[Route('/post/edit/{post}')]
    public function edit (
        Post $post,
        Request $request,
        ManagerRegistry $doctrine
    ): Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em = $doctrine->getManager();
            $em->flush();
            return new RedirectResponse("/post/list");
        }

        return $this->renderForm("post/form.html.twig", ['form' => $form, 'label' => 'Edit']);
    }
}