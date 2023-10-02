<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(PostType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em->persist($post);
            $em->flush();
            $this->addFlash('notice', 'Post successfully created.');
            return $this->redirectToRoute('app_post_list');
        }

        return $this->render('Post/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/post/list')]
    public function list(PostRepository $rep): Response
    {
        $posts = $rep->findAll();

        return $this->render('Post/list.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/post/update/{post}')]
    public function update(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }

        return $this->render('Post/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/post/delete/{post}')]
    public function delete(Post $post, EntityManagerInterface $em): Response
    {
        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute("app_post_list");
    }

    #[Route('/post/{post}')]
    public function read(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CommentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $em->persist($comment);
            $em->flush();
        }

        return $this->render('Post/read.html.twig', [
            'post' => $post,
            'form' => $form
        ]);
    }
}