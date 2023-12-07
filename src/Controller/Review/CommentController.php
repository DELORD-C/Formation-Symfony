<?php

namespace App\Controller\Review;

use App\Entity\Review\Comment;
use App\Entity\Review;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reviewcomment')]
class CommentController extends AbstractController {
    #[Route('/create/{review}')]
    #[IsGranted('createComment')]
    public function create (Review $review, Request $request, EntityManagerInterface $em): Response
    {
        $comment = new Comment;

        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('app_review_comment_create', ['review' => $review->getId()])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setReview($review);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('app_review_read', ['review' => $review->getId()]);
        }

        return $this->render('Comment/create.html.twig', [
            'commentForm' => $form
        ]);
    }

    #[Route('/delete/{comment}')]
    #[IsGranted('delete', 'comment')]
    public function delete (Comment $comment, EntityManagerInterface $em): Response
    {
        $em->remove($comment);
        $em->flush();
        return $this->redirectToRoute('app_review_read', ['review' => $comment->getReview()->getId()]);
    }
}