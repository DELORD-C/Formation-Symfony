<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/create', methods: ['GET', 'HEAD'])]
    public function create (): Response
    {
        return new Response("
<html><body>
    <form method='post'>
        <input type='text' name='subject'>
        <textarea name='body'></textarea>
        <input type='submit' content='Create'>
    </form>        
</body></html>
        ");
    }

    #[Route('/post/create', methods: ['POST'])]
    public function store (Request $request, ManagerRegistry $doctrine): Response
    {
        $post = new Post();
        $post->setBody($request->get('body'));
        $post->setSubject($request->get('subject'));

        $em = $doctrine->getManager();

        $em->persist($post);
        $em->flush();

        return new RedirectResponse("/post/create");
    }

    #[Route('/post/list')]
    public function list (PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        $response = '
<html><body><table>
    <tr>
        <th>Subject</th>
        <th>Body</th>
        <th>Date</th>
        <th>Action</th>
    </tr>';

        foreach ($posts as $post) {
            $response .= '
            <tr>
                <td>' . $post->getSubject() . '</td>
                <td>' . $post->getBody() . '</td>
                <td>' . $post->getCreatedAt()->format('F d y') . '</td>
                <td>
                    <a href="/post/' . $post->getId() . '">Show</a>
                    <form method="DELETE" action="/post/' . $post->getId() . '">
                       <input type="submit" content="Delete">
                    </form>
                </td>
            </tr>
            ';
        }
        $response .= '
</table></body></html>
        ';

        return new Response($response);
    }

    #[Route('/post/{post}', methods: ['DELETE'])]
    public function delete (Post $post, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($post);
        $em->flush();
        return new RedirectResponse('/post/list');
    }

    #[Route('/post/{post}')]
    public function show (Post $post): Response
    {
        $date = $post->getCreatedAt()->format('F d y');
        return new Response('
<html><body><table>
    <tr>
        <th>Subject</th>
        <th>Body</th>
        <th>Date</th>
    </tr>
    <tr>
        <td>' . $post->getSubject() . '</td>
        <td>' . $post->getBody() . '</td>
        <td>' . $date . '</td>
    </tr>
</table></body></html>');
    }
}