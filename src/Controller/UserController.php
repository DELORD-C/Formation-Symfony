<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/create')]
    public function create (Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword(
                $passwordHasher->hashPassword($user, $user->getPassword())
            );
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'User successfully created !');

            return new RedirectResponse("/user/list");
        }

        return $this->renderForm("user/form.html.twig", ['form' => $form, 'label' => 'Create']);
    }

    #[Route('/user/list')]
    public function list (UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render("user/list.html.twig", ['users' => $users]);
    }

    #[Route('/user/delete/{user}', methods: ['POST'])]
    public function delete (User $user, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($user);
        $em->flush();
        $this->addFlash('notice', 'User successfully deleted !');
        return new RedirectResponse('/user/list');
    }

    #[Route('/user/{user}')]
    public function show (User $user): Response
    {
        return $this->render("user/show.html.twig", [
            'user' => $user,
        ]);
    }

    #[Route('/user/edit/{user}')]
    public function edit (
        User $user,
        Request $request,
        ManagerRegistry $doctrine
    ): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $doctrine->getManager();
            $em->flush();
            $this->addFlash('notice', 'User successfully updated !');
            return new RedirectResponse("/user/list");
        }

        return $this->renderForm("user/form.html.twig", ['form' => $form, 'label' => 'Edit']);
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
//        if (in_array('ROLE_USER', $this->getUser()->getRoles())) {
        if ($this->isGranted('ROLE_USER')) {
            $this->addFlash('error', 'You are already logged in');
            return $this->redirectToRoute('app_post_list');
        }
        else {
            return $this->render('auth/login.html.twig');
        }
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \Exception('THIS SHALL NOT BE.');
    }

    #[Route('/user/toggle/{user}')]
    public function toggleAdmin(User $user, ManagerRegistry $doctrine): Response
    {
        if ($user === $this->getUser()) {
            $this->addFlash('error', 'You can\' change your own rights');
            return $this->redirectToRoute('app_user_list');
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $user->setRoles([]);
        }
        else {
            $user->setRoles(["ROLE_ADMIN"]);
        }
        $em = $doctrine->getManager();
        $em->flush();

        return $this->redirectToRoute('app_user_list');
    }
}