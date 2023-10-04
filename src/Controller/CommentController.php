<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/create/{post}')]
    public function create(Post $post, Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(CommentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();
            $this->addFlash('notice', 'Comment successfully created.');
        }

        return $this->redirectToRoute('app_post_read', ['post' => $post->getId()]);
    }

    #[Route('/update/{comment}')]
    public function update(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('UPDATE', $comment);
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_post_read', ['post' => $comment->getPost()->getId()]);
        }

        return $this->render('Comment/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{comment}')]
    public function delete(Comment $comment, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $comment);
        $em->remove($comment);
        $em->flush();
        return $this->redirectToRoute("app_post_read", ['post' => $comment->getPost()->getId()]);
    }

    #[Route('/like/{comment}')]
    public function like(Comment $comment, EntityManagerInterface $em, LikeRepository $rep): Response
    {
        $like = $rep->findOneBy(['comment' => $comment, 'user' => $this->getUser()]);

        if ($like != null) {
            $em->remove($like);
        }
        else {
            $like = new Like();
            $like->setComment($comment);
            $like->setUser($this->getUser());
            $em->persist($like);
        }
        $em->flush();

        return $this->redirectToRoute('app_post_read', ['post' => $comment->getPost()->getId()]);
    }
}