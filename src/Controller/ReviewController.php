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
class ReviewController extends AbstractController
{
    #[Route('/create')]
    #[IsGranted('CREATE')]
    function create(Request $request, EntityManagerInterface $em): Response
    {
        $review = new Review();

        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form->getData();
            $review->setUser($this->getUser());
            $em->persist($review);
            $em->flush();
//            return $this->redirect('/review/create');
            $this->addFlash('notice', 'Review successfully created');
            return $this->redirectToRoute('app_review_create');
        }

        return $this->render('Review/form.html.twig', [
            'reviewForm' => $form
        ]);
    }

    #[Route('/all')]
    #[IsGranted('READ')]
    function all(ReviewRepository $rep, Request $request): Response
    {

        $reviews = $rep->findAll();
        $response = $this->render('Review/all.html.twig', [
            'reviews' => $reviews
        ]);
        $response->setPublic();
        $response->setEtag(md5($response->getContent()));
        $response->isNotModified($request);

        return $response;
    }

    #[Route('/read/{review}')]
    #[IsGranted('READ')]
    function read(Review $review): Response
    {
        return $this->render('Review/read.html.twig', [
            'review' => $review
        ]);
    }

    #[Route('/update/{review}')]
    #[IsGranted('UPDATE', 'review', 'You can only update your own reviews !', 403)]
    function update(Review $review, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form->getData();
            $em->persist($review);
            $em->flush();
            $this->addFlash('notice', 'Review ' . $review->getId() . ' successfully updated');
            return $this->redirectToRoute('app_review_update', ['review' => $review->getId()]);
        }

        return $this->render('Review/form.html.twig', [
            'reviewForm' => $form
        ]);
    }

    #[Route('/delete/{review}')]
    #[IsGranted('DELETE', 'review', 'You can only delete your own reviews !', 403)]
    function delete(Review $review, EntityManagerInterface $em): Response
    {
        $em->remove($review);
        $em->flush();
        $this->addFlash('notice', 'Review ' . $review->getId() . ' successfully deleted');
        return $this->redirectToRoute('app_review_all');
    }
}