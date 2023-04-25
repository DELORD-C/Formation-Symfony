<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/post')]
class PostController extends AbstractController {

    #[Route('/create', methods: ['GET', 'HEAD'])]
    function create ()
    {
        return $this->render('post/create.html.twig');
    }

    #[Route('/create', methods: ['POST'])]
    function store (Request $request, EntityManagerInterface $em)
    {
        dump($request);

        $post = new Post();
        $post->setTitle($request->get('title'));
        $post->setBody($request->get('body'));
        $em->persist($post);
        $em->flush();

        return $this->render('default/variable.html.twig', [
            'variable' => ''
        ]);
    }
}