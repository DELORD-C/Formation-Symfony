<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/post')]
class PostController extends AbstractController {

    #[Route('/create')]
    function create (Request $request, EntityManagerInterface $em)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em->persist($post);
            $em->flush();
            $this->addFlash('notice', 'Post successfully created!');
            return $this->redirect('/post/create');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form
        ]);
    }
}