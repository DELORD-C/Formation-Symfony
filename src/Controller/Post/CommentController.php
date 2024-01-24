<?php

namespace App\Controller\Post;

use App\Entity\Post;
use App\Entity\Post\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/post/comment')]
class CommentController extends AbstractController {
    function create (Post $post): Response {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('post/comment/create.html.twig', [
            'post' => $post,
            'form' => $form
        ]);
    }

    #[Route('/store/{post}')]
    function store (Post $post, Request $request, EntityManagerInterface $em): Response {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post);
            $em->persist($comment);
            $em->flush();
            $this->addFlash('notice', 'Comment successfully created !');
        }

        return $this->redirectToRoute('app_post_read', ['post' => $post->getId()]);
    }

    #[Route('/delete/{comment}')]
    function delete (Comment $comment, EntityManagerInterface $em): Response {
        $em->remove($comment);
        $em->flush();
        $this->addFlash('notice', 'Comment successfully removed !');
        return $this->redirectToRoute('app_post_read', ['post' => $comment->getPost()->getId()]);
    }
}