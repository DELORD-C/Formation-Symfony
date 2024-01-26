<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Service\WeatherApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/weather/{lat}/{lon}')]
    function weather (WeatherApi $api, string $lat = '48.85', string $lon = '2.29'): Response
    {
        $response = new Response($api->getCurrentRaw($lat, $lon));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    #[Route('/weatherPost')]
    function weatherPost (WeatherApi $api, Request $request): Response
    {
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');
        $response = new Response($api->getCurrentRaw($lat, $lon));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    #[Route('/post/all')]
    function allPosts (PostRepository $rep, SerializerInterface $serializer): Response
    {
        $posts = $rep->findAll();
        $encoder = new JsonEncoder();

        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function($object, $format, $context) {
                return $object->getId();
            }
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);

        $json = $serializer->serialize($posts, 'json');

        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }
}