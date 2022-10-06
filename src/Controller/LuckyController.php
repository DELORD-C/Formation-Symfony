<?php

namespace App\Controller;

use App\Custom\Tree;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $number = random_int(0, 10);

        return $this->render(
            'lucky/number.html.twig',
            [
                'title' => 'Lucky number',
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

        return $this->render(
            'display.html.twig',
            [
                'title' => 'Random Welcome',
                'data' => $sentences[$number]
            ]
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

        return $this->render(
            'display.html.twig',
            [
                'title' => 'Random Eight',
                'data' => $str
            ]
        );
    }

    /**
     * @return Response
     * @throws \Exception
     * @Route("/lucky/eight/{number}")
     */
    public function eight(Tree $tree, Int $number = 1): Response
    {
        $str = $tree->generate($number);

        return $this->render(
            'display.html.twig',
            [
                'title' => 'Eight',
                'data' => $str
            ]
        );
    }
    /**
     * @return Response
     * @throws \Exception
     * @Route("/lucky/ip")
     */
    public function ip (Request $request): Response
    {

        $ip = intval(substr($request->getClientIp(), -1));

        if ($ip % 2 == 0) {
            $isPair = true;
        }
        else {
            $isPair = false;
        }

        return $this->render(
            'lucky/ip.html.twig',
            [
                'title' => 'IP',
                'isPair' => $isPair
            ]
        );
    }

}