<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    function create(): Response
    {
        $form = $this->createForm(CommentType::class, new Comment());

        return $this->render('Comment/_form.html.twig', [
            'commentForm' => $form
        ]);
    }

    #[Route('comment/list/{post}')]
    function list(Post $post, CommentRepository $rep): Response
    {
        $comments = $rep->findBy(['post' => $post]);
        return $this->render('Comment/_all.html.twig', [
            'comments' => $comments
        ]);
    }

    #[Route('post/read/{post}', methods: ['POST'])]
    function saveComment (Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $em->persist($comment);
            $em->flush();
            $this->addFlash('notice', 'Comment successfully created');
        }
        return $this->redirectToRoute('app_post_read', ['post' => $post->getId()]);
    }

    #[Route('comment/update/{comment}')]
    function update(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $em->persist($comment);
            $em->flush();
            $this->addFlash('notice', 'Comment ' . $comment->getId() . ' successfully updated');
            return $this->redirectToRoute('app_post_read', ['post' => $comment->getPost()->getId()]);
        }

        return $this->render('Comment/form.html.twig', [
            'commentForm' => $form
        ]);
    }

    #[Route('comment/delete/{comment}')]
    function delete(Comment $comment, EntityManagerInterface $em): Response
    {
        $em->remove($comment);
        $em->flush();
        $this->addFlash('notice', 'Comment ' . $comment->getId() . ' successfully deleted');
        return $this->redirectToRoute('app_post_read', ['post' => $comment->getPost()->getId()]);
    }
}