<?php

namespace App\Controller\Review;

use App\Entity\Review;
use App\Entity\Review\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/review/comment')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class CommentController extends AbstractController {
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    function create (Review $review): Response {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('review/comment/create.html.twig', [
            'review' => $review,
            'form' => $form
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/store/{review}')]
    function store (Review $review, Request $request, EntityManagerInterface $em): Response {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setReview($review);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();
            $this->addFlash('notice', 'Comment successfully created !');
        }

        return $this->redirectToRoute('app_review_read', ['review' => $review->getId()]);
    }

    #[IsGranted('DELETE', 'comment')]
    #[Route('/delete/{comment}')]
    function delete (Comment $comment, EntityManagerInterface $em): Response {
        $em->remove($comment);
        $em->flush();
        $this->addFlash('notice', 'Comment successfully removed !');
        return $this->redirectToRoute('app_review_read', ['review' => $comment->getReview()->getId()]);
    }

    #[Route('/update/{comment}')]
    #[IsGranted('UPDATE', 'comment')]
    function update (Comment $comment, Request $request, EntityManagerInterface $em): Response {
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('notice', 'Comment successfully updated !');
            return $this->redirectToRoute('app_post_read', ['post' => $comment->getReview()->getId()]);
        }

        return $this->render('default/form.html.twig', [
            'title' => 'Update comment',
            'form' => $form
        ]);
    }
}