<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController
{
    #[Route('/post/{post}')]
    public function show (Post $post): Response
    {
        return new Response('
<html><body><table>
    <tr>
        <th>Subject</th>
        <th>Body</th>
    </tr>
    <tr>
        <td>' . $post->getSubject() . '</td>
        <td>' . $post->getBody() . '</td>
    </tr>
</table></body></html>');
    }
}