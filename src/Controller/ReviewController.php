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

class ReviewController extends AbstractController
{
    #[Route('/review/create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(ReviewType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form->getData();
            $em->persist($review);
            $em->flush();
            $this->addFlash('notice', 'Review successfully created.');
            return $this->redirectToRoute('app_review_list');
        }

        return $this->render('Review/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/review/list')]
    public function list(ReviewRepository $rep): Response
    {
        $reviews = $rep->findAll();

        return $this->render('Review/list.html.twig', [
            'reviews' => $reviews
        ]);
    }

    #[Route('/review/update/{review}')]
    public function update(Review $review, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }

        return $this->render('Review/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/review/delete/{review}')]
    public function delete(Review $review, EntityManagerInterface $em): Response
    {
        $em->remove($review);
        $em->flush();
        return $this->redirectToRoute("app_review_list");
    }
}