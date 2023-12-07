<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/review')]
class ReviewController extends AbstractController {
    #[Route('/create')]
    #[IsGranted('createReview')]
    public function create (Request $request, EntityManagerInterface $em): Response
    {

        $review = new Review();

        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form->getData();
            $review->setUser($this->getUser());
            $em->persist($review);
            $em->flush();

            return $this->redirectToRoute('app_review_list');
        }

        return $this->render('Review/create.html.twig', [
            'reviewForm' => $form
        ]);
    }

    #[Route('/list')]
    public function list (ReviewRepository $rep): Response
    {
        $reviews = $rep->findAll();

        return $this->render('Review/list.html.twig', [
            'reviews' => $reviews
        ]);
    }

    #[Route('/read/{review}')]
    public function read (Review $review): Response
    {
        return $this->render('Review/read.html.twig', ['review' => $review]);
    }

    #[Route('/delete/{review}')]
    #[IsGranted('editOrDelete', 'review')]
    public function delete (Review $review, EntityManagerInterface $em): Response
    {
        // if ($review->getUser() !== $this->getUser()) {
        //     $this->addFlash('error', 'You can\'t delete this review.');
        //     return $this->redirectToRoute('app_user_login');
        // }
        $em->remove($review);
        $em->flush();
        return $this->redirectToRoute('app_review_list');
    }

    #[Route('/update/{review}')]
    #[IsGranted('editOrDelete', 'review')]
    public function update (Review $review, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form->getData();
            $em->persist($review);
            $em->flush();
        }

        return $this->render('Review/create.html.twig', [
            'reviewForm' => $form
        ]);
    }
}