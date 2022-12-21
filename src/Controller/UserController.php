<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;

class UserController extends AbstractController
{
    #[Route('/register')]
    public function register (Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('notice', 'Account created.');
            return $this->redirectToRoute("app_default_home");
        }

        return $this->render('user/register.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/login')]
    public function login(): Response
    {
        return $this->render('user/login.html.twig');
    }

    #[Route('/logout')]
    public function logout(){}

    #[Route('/user/delete/{user}')]
    public function delete (User $user, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $this->addFlash('notice', 'User n°' . $user->getId() . ' successfully deleted.');
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_user_list');
    }

    #[Route('/user/list')]
    public function list (UserRepository $userRepository, PostRepository $postRepository): Response
    {
        $users = $userRepository->findAll();
        foreach ($users as $user) {
            $user->nbPost = count($postRepository->findBy(['user' => $user]));
        }
        return $this->render('user/list.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/user/{user}')]
    public function read (User $user): Response
    {
        return $this->render('user/read.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/user/edit/{user}')]
    public function edit (User $user, Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            $this->addFlash('notice', 'User successfully updated.');
            return $this->redirectToRoute("app_user_list");
        }

        return $this->render('user/register.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/user/admin/{user}')]
    public function grantRevoke (User $user, ManagerRegistry $doctrine): Response
    {
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles)) {
            $key = array_search('ROLE_ADMIN', $roles);
            unset($roles[$key]);
        }
        else {
            array_push($roles, 'ROLE_ADMIN');
        }
        $user->setRoles($roles);
        $em = $doctrine->getManager();
        $em->flush();
    }
    
}