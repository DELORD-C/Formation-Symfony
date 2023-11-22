<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[IsGranted('READ')]
    function list(Post $post, CommentRepository $rep): Response
    {
        $comments = $rep->findBy(['post' => $post]);

        foreach ($comments as $comment) {
            $comment->isLiked = false;
            foreach ($comment->getLikes() as $like) {
                if ($like->getUser() === $this->getUser()) {
                    $comment->isLiked = true;
                }
            }
        }

        return $this->render('Comment/_all.html.twig', [
            'comments' => $comments
        ]);
    }

    #[Route('post/read/{post}', methods: ['POST'])]
    #[IsGranted('CREATE')]
    function saveComment (Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();
            $this->addFlash('notice', 'Comment successfully created');
        }
        return $this->redirectToRoute('app_post_read', ['post' => $post->getId()]);
    }

    #[Route('comment/update/{comment}')]
    #[IsGranted('UPDATE', 'comment', 'You can only update your own comments !', 403)]
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
    #[IsGranted('DELETE', 'comment', 'You can only delete your own comments or comments on your posts !', 403)]
    function delete(Comment $comment, EntityManagerInterface $em): Response
    {
        $em->remove($comment);
        $em->flush();
        $this->addFlash('notice', 'Comment ' . $comment->getId() . ' successfully deleted');
        return $this->redirectToRoute('app_post_read', ['post' => $comment->getPost()->getId()]);
    }

    #[Route('comment/like/{comment}')]
    function likeToggle(Comment $comment, LikeRepository $rep, EntityManagerInterface $em): Response
    {
        $like = $rep->findOneBy(['user' => $this->getUser(), 'comment' => $comment]);
        if ($like) {
            $em->remove($like);
        }
        else {
            $like = new Like();
            $like->setUser($this->getUser());
            $like->setComment($comment);
            $em->persist($like);
        }
        $em->flush();
        return $this->redirectToRoute('app_post_read', ['post' => $comment->getPost()->getId()]);
    }
}