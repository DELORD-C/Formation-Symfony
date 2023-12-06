<?php

namespace App\Controller;
use App\Entity\Like;
use App\Entity\Post\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController {
    #[Route('/like/{comment}')]
    public function create (Comment $comment, EntityManagerInterface $em): Response
    {
        $like = new Like;
        $like->setComment($comment);
        $em->persist($like);
        $em->flush();
        return $this->redirectToRoute('app_post_read', ['post' => $comment->getPost()->getId()]);
    }
}