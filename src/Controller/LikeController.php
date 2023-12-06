<?php

namespace App\Controller;
use App\Entity\Like;
use App\Entity\Post\Comment;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController {
    #[Route('/like/{comment}')]
    public function toggle (
        Comment $comment,
        EntityManagerInterface $em,
        LikeRepository $rep
    ): Response
    {
        $testLike = $rep->findOneBy([
            "user" => $this->getUser(),
            "comment" => $comment
        ]);

        if ($testLike) {
            $em->remove($testLike);
        }
        else {
            $like = new Like;
            $like->setUser($this->getUser());
            $like->setComment($comment);
            $em->persist($like);
        }
        
        $em->flush();
        return $this->redirectToRoute('app_post_read', ['post' => $comment->getPost()->getId()]);
    }
}