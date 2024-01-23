<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/comment')]
class CommentController extends AbstractController {
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
            return $this->redirectToRoute('app_post_read', ['post' => $post->getId()]);
        }
    }
}