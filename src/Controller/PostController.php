<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/create')]
    function create (Request $request, EntityManagerInterface $em): Response {

        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($post);
            $em->flush(); // Applique toutes les modifications en attente et vide la cache de la base de donnée
            $this->addFlash('notice', 'Post successfully created !');
            return $this->redirectToRoute('app_post_list');
        }

        return $this->render('post/create.html.twig', [
            'postForm' => $form->createView()
        ]);
    }

    #[Route('/list')]
    function list (PostRepository $rep): Response {
        $posts = $rep->findAll();
        return $this->render('post/list.html.twig', [
           'posts' => $posts
        ]);
    }

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

        return $this->render('post/create.html.twig', [
            'postForm' => $form->createView()
        ]);
    }

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
        $form = $this->createForm(CommentType::class, new Comment());

        return $this->render('post/read.html.twig', [
            'post' => $post,
            'commentForm' => $form->createView()
        ]);
    }
}