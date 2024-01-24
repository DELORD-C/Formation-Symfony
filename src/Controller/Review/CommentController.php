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

#[Route('/review/comment')]
class CommentController extends AbstractController {
    function create (Review $review): Response {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('review/comment/create.html.twig', [
            'review' => $review,
            'form' => $form
        ]);
    }

    #[Route('/store/{review}')]
    function store (Review $review, Request $request, EntityManagerInterface $em): Response {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setReview($review);
            $em->persist($comment);
            $em->flush();
            $this->addFlash('notice', 'Comment successfully created !');
        }

        return $this->redirectToRoute('app_review_read', ['review' => $review->getId()]);
    }
}