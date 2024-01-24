<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/review')]
class ReviewController extends AbstractController
{
    #[Route('/create')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    function create (Request $request, EntityManagerInterface $em): Response {

        $review = new Review();

        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUser($this->getUser());
            $em->persist($review);
            $em->flush();
            $this->addFlash('notice', 'Review successfully created !');
            return $this->redirectToRoute('app_review_list');
        }

        return $this->render('default/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Create review'
        ]);
    }

    #[Route('/list')]
    function list (ReviewRepository $rep): Response {
        $reviews = $rep->findAll();
        return $this->render('review/list.html.twig', [
           'reviews' => $reviews
        ]);
    }

    #[IsGranted('UPDATE', 'review')]
    #[Route('/update/{review}')]
    function update (Review $review, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('notice', 'Review successfully updated !');
            return $this->redirectToRoute('app_review_list');
        }

        return $this->render('default/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Update review'
        ]);
    }

    #[IsGranted('DELETE', 'review')]
    #[Route('/delete/{review}')]
    function delete (Review $review, EntityManagerInterface $em): Response
    {
        $em->remove($review);
        $em->flush();
        $this->addFlash('notice', 'Review successfully removed !');
        return $this->redirectToRoute('app_review_list');
    }

    #[Route('/read/{review}')]
    function read (Review $review): Response
    {
        return $this->render('review/read.html.twig', [
            'review' => $review
        ]);
    }
}