<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LuckyController extends AbstractController
{
    /**
     * @return Response
     * @throws \Exception
     * @Route("/lucky/number")
     */
    public function number(): Response
    {
        $number = random_int(0, 100);

        return $this->render(
            'lucky/number.html.twig',
            [
                'number' => $number
            ]
        );
    }

    /**
     * @return Response
     * @throws \Exception
     * @Route("/lucky/welcome")
     */
    public function welcome(): Response
    {
        $number = random_int(0, 4);

        $sentences = [
            'Hello',
            'Bonjour',
            'Gutten Tag',
            'Yo',
            'Ola'
        ];

        return new Response(
            "<html lang='en'><body>" . $sentences[$number] . "</body></html>"
        );
    }

    /**
     * @return Response
     * @throws \Exception
     * @Route("/lucky/eight/random")
     */
    public function eightRandom(): Response
    {
        $number = random_int(0, 100);

        $str = "";
        for ($i = 0; $i < $number; $i++) {
            $str .= '8';
        }

        return new Response(
            "<html lang='en'><body>$str</body></html>"
        );
    }

    /**
     * @return Response
     * @throws \Exception
     * @Route("/lucky/eight/{number}")
     */
    public function eight(Int $number = 1): Response
    {
        $str = "";
        for ($i = 0; $i < $number; $i++) {
            $str .= '8';
        }

        return new Response(
            "<html lang='en'><body>$str</body></html>"
        );
    }

}