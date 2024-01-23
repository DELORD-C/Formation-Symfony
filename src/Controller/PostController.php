<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/create')]
    function create (Request $request, EntityManagerInterface $em): Response {

        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em->persist($post);
            $em->flush(); // Applique toutes les modifications en attente et vide la cache de la base de donnée
            $this->addFlash('notice', 'Post successfully created !');
            return $this->redirectToRoute('app_post_create');
        }

        return $this->render('post/create.html.twig', [
            'postForm' => $form->createView()
        ]);
    }

    #[Route('/list')]
    function list (PostRepository $rep): Response {
        $posts = $rep->findAll();
        return $this->render('post/list.html.twig', [
           'posts' => $posts
        ]);
    }
}