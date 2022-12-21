<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/create')]
    public function create (Request $request, ManagerRegistry $doctrine): Response
    {
        $post = new Post();
        $this->denyAccessUnlessGranted('CREATE', $post);

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setUser($this->getUser());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('notice', 'Post successfully created.');
            return $this->redirectToRoute("app_post_list");
        }

        return $this->render('post/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/post/list')]
    public function list (PostRepository $postRepository): Response
    {
        $this->denyAccessUnlessGranted('SHOW', new Post);
        $posts = $postRepository->findAll();
        return $this->render('post/list.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/post/delete/{post}')]
    public function delete (Post $post, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $post);
        $em = $doctrine->getManager();
        $this->addFlash('notice', 'Post n°' . $post->getId() . ' successfully deleted.');
        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute('app_post_list');
    }

    #[Route('/post/{post}')]
    public function read (Post $post): Response
    {
        $this->denyAccessUnlessGranted('SHOW', $post);
        return $this->render('post/read.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/post/edit/{post}')]
    public function edit (Post $post, Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $post);
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            $this->addFlash('notice', 'Post successfully updated.');
            return $this->redirectToRoute("app_post_list");
        }

        return $this->render('post/create.html.twig', [
            'form' => $form
        ]);
    }
}