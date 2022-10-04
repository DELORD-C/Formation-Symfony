<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/create')]
    function create (Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_user_create');
        }

        return $this->renderForm('user/create.html.twig', [
           'title' => 'User creation',
           'form' => $form
        ]);
    }

    #[Route('/user/list')]
    function list (UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/list.html.twig', [
            'title' => 'User list',
            'users' => $users
        ]);
    }

    #[Route('/user/edit/{user}')]
    function edit (User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_user_list');
        }

        return $this->renderForm('user/create.html.twig', [
            'title' => 'User edition',
            'form' => $form
        ]);
    }

    #[Route('/user/delete/{user}')]
    function delete (User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_user_list');
    }
}