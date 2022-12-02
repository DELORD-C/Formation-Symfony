<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    #[Route('/api/posts')]
    public function posts (PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getEmail();
            },
        ];
        $normalizer = new ObjectNormalizer(
            null,
            null,
            null,
            null,
            null,
            null,
            $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $output = $serializer->serialize($posts, 'json');
        $response = new Response();
        $response->setContent($output);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
//        return new JsonResponse($output);
    }
}