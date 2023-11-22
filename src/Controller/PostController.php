<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/create')]
    #[IsGranted('CREATE')]
    function create(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setUser($this->getUser());
            $em->persist($post);
            $em->flush();
//            return $this->redirect('/post/create');
            $this->addFlash('notice', 'Post successfully created');
            return $this->redirectToRoute('app_post_create');
        }

        return $this->render('Post/form.html.twig', [
            'postForm' => $form
        ]);
    }

    #[Route('/all')]
    #[IsGranted('READ')]
    function all(PostRepository $rep): Response
    {
        $posts = $rep->findAll();
        return $this->render('Post/all.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/read/{post}', methods: ['GET'])]
    #[IsGranted('READ')]
    function read(Post $post): Response
    {
        return $this->render('Post/read.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/update/{post}')]
    #[IsGranted('UPDATE', 'post', 'You can only update your own posts !', 403)]
    function update(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em->persist($post);
            $em->flush();
            $this->addFlash('notice', 'Post ' . $post->getId() . ' successfully updated');
            return $this->redirectToRoute('app_post_update', ['post' => $post->getId()]);
        }

        return $this->render('Post/form.html.twig', [
            'postForm' => $form
        ]);
    }

    #[Route('/delete/{post}')]
    #[IsGranted('DELETE', 'post', 'You can only delete your own posts !', 403)]
    function delete(Post $post, EntityManagerInterface $em): Response
    {
        $em->remove($post);
        $em->flush();
        $this->addFlash('notice', 'Post ' . $post->getId() . ' successfully deleted');
        return $this->redirectToRoute('app_post_all');
    }
}