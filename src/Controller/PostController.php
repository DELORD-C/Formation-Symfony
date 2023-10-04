<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
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
    public function create(Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(PostType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setUser($this->getUser());
            $em->persist($post);
            $em->flush();
            $this->addFlash('notice', 'Post successfully created.');
            return $this->redirectToRoute('app_post_list');
        }

        return $this->render('Post/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/list')]
    public function list(PostRepository $rep, CommentRepository $commentRep): Response
    {
        $posts = $rep->findAll();

        foreach ($posts as &$post) {
            $post->comments = $commentRep->countByPost($post);
        }

        return $this->render('Post/list.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/recent')]
    public function recent(PostRepository $rep): Response
    {
        $posts = $rep->findRecent();

        return $this->render('Post/list.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/update/{post}')]
//    #[IsGranted('UPDATE')]
    public function update(Post $post, Request $request, EntityManagerInterface $em): Response
    {

        $this->denyAccessUnlessGranted('UPDATE', $post);
//        if ($post->getUser() !== $this->getUser()) {
//            $this->addFlash('error', 'You only can update your own posts !');
//            return $this->redirectToRoute('app_post_list');
//        }

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }

        return $this->render('Post/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{post}')]
    public function delete(Post $post, EntityManagerInterface $em): Response
    {
        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute("app_post_list");
    }

    #[Route('/{post}')]
    public function read(Post $post, CommentRepository $rep, LikeRepository $likeRep): Response
    {
        $form = $this->createForm(CommentType::class);

        $comments = $rep->findBy(['post' => $post]);

        foreach ($comments as &$comment) {
            $comment->likes = $likeRep->findUsersWhoLiked($comment);
        }

        return $this->render('Post/read.html.twig', [
            'comments' => $comments,
            'post' => $post,
            'form' => $form
        ]);
    }
}