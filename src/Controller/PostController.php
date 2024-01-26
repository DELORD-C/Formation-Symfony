<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/create')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    function create (Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $em->persist($post);
            $em->flush(); // Applique toutes les modifications en attente et vide la cache de la base de donnée
            $this->addFlash('notice', 'Post successfully created !');
            return $this->redirectToRoute('app_post_list');
        }

        return $this->render('default/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Create post'
        ]);
    }

    #[Route('/list')]
    function list (PostRepository $rep, Request $request): Response
    {
        $posts = $rep->findAll();

        $response = $this->render('post/list.html.twig', [
           'posts' => $posts
        ]);
        $etag = md5($response->getContent());
        $response->setEtag($etag);
        $response->setPublic();
        $response->isNotModified($request);

        return $response;
    }

    #[IsGranted('UPDATE', 'post')]
    #[Route('/update/{post}')]
    function update (Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('notice', 'Post successfully updated !');
            return $this->redirectToRoute('app_post_list');
        }

        return $this->render('default/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Update post'
        ]);
    }

    #[IsGranted('DELETE', 'post')]
    #[Route('/delete/{post}')]
    function delete (Post $post, EntityManagerInterface $em): Response
    {
        $em->remove($post);
        $em->flush();
        $this->addFlash('notice', 'Post successfully removed !');
        return $this->redirectToRoute('app_post_list');
    }

    #[Route('/read/{post}')]
    function read (Post $post): Response
    {
        return $this->render('post/read.html.twig', [
            'post' => $post
        ]);
    }
}