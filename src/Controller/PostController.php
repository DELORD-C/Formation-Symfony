<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/create')]
    function create (Request $request, EntityManagerInterface $em, UserRepository $userRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $user = $userRepository->find(1);
            $post->setUser($user);
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('app_post_list');
        }

        return $this->renderForm('post/create.html.twig', [
           'title' => 'Post creation',
           'form' => $form
        ]);
    }

    #[Route('/')]
    function list (PostRepository $postRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $posts = $postRepository->findAll();

        return $this->render('post/list.html.twig', [
            'title' => 'Post list',
            'posts' => $posts
        ]);
    }

    #[Route('/post/edit/{post}')]
    function edit (Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('app_post_list');
        }

        return $this->renderForm('post/create.html.twig', [
            'title' => 'Post edition',
            'form' => $form
        ]);
    }

    #[Route('/post/delete/{post}')]
    function delete (Post $post, EntityManagerInterface $em): Response
    {
        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute('app_post_list');
    }
}