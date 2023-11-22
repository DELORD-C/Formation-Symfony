<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


#[Route('/api')]
class OldApiController extends AbstractController
{
    #[Route('/users')]
    function users (UserRepository $rep): Response
    {
        $users = $rep->findAll();

        $encoder = new JsonEncoder();

        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function($object, $format, $context) {
            return $object->getId();
            }
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);

        $json = $serializer->serialize($users, 'json');

        return new Response($json);
    }
}