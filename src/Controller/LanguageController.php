<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LanguageController extends AbstractController
{
    #[Route('/switch/{lang}')]
    function switch (String $lang, Request $request): Response
    {
        $request->getSession()->set('_locale', $lang);
        if (!empty($request->get('url'))) {
            $url = $request->get('url');
        }
        else {
            $url = '/';
        }
        return $this->redirect($url);
    }
}