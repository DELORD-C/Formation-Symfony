<?php

namespace App\Controller\Post;

use App\Entity\Post\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController {
    #[Route('/create/{post}')]
    public function create (Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $comment = new Comment;

        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('app_post_comment_create', ['post' => $post->getId()])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('app_post_read', ['post' => $post->getId()]);
        }

        return $this->render('Comment/create.html.twig', [
            'commentForm' => $form
        ]);
    }

    #[Route('/delete/{comment}')]
    public function delete (Comment $comment, EntityManagerInterface $em): Response
    {
        $em->remove($comment);
        $em->flush();
        return $this->redirectToRoute('app_post_read', ['post' => $comment->getPost()->getId()]);
    }
}