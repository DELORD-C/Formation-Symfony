<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/posts')]
    public function posts (PostRepository $postRepository, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('API');
        if ($request->get('id')) {
            $posts = $postRepository->findBy(['id' => $request->get('id')]);
        }
        else {
            $posts = $postRepository->findAll();
        }
        if (empty($posts)) {
            $posts = ['empty results'];
        }
        return $this->json($posts);
    }

    #[Route('/messages')]
    public function messages (MessageRepository $repository): JsonResponse
    {
        $objects = $repository->findAll();
        return $this->json($objects);
    }

    #[Route('/users')]
    public function users (UserRepository $repository): JsonResponse
    {
        $objects = $repository->findAll();
        return $this->json($objects);
    }
}