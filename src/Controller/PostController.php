<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Repository\PostRepository;
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
            'form' => $form,
            'title' => 'Create new Post'
        ]);
    }

    #[Route('/list')]
    function list (PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();
        return $this->render('post/list.html.twig', [
            'posts' => $posts,
            'title' => 'All Posts'
        ]);
    }

    #[Route('/edit/{post}')]
    function edit (Post $post, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em->persist($post);
            $em->flush();
            $this->addFlash('notice', 'Post successfully edited!');
            return $this->redirect($request->getUri());
        }

        return $this->render('post/create.html.twig', [
            'form' => $form,
            'title' => 'Edit Post'
        ]);
    }

    #[Route('/delete/{post}')]
    function delete (Post $post, EntityManagerInterface $em)
    {
        $em->remove($post);
        $em->flush();
        $this->addFlash('notice', 'Post successfully deleted!');
        return $this->redirectToRoute('app_post_list');
    }

    #[Route('/search')]
    function search (Request $request, PostRepository $rep)
    {
        $posts = $rep->search($request->get('query'));
        dump($posts);

        return $this->render('post/list.html.twig', [
            'posts' => $posts,
            'title' => 'Search results'
        ]);
    }
}