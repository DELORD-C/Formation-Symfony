<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[Route('/login')]
    public function login(AuthenticationUtils $utils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('notice', 'You are already logged in !');
            return $this->redirectToRoute('app_default_hello');
        }
        return $this->render('User/login.html.twig', [
            'lastUsername' => $utils->getLastUsername(),
            'error' => $utils->getLastAuthenticationError()
        ]);
    }

    #[Route('/register')]
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        if ($this->getUser()) {
            $this->addFlash('notice', 'You are already logged in !');
            return $this->redirectToRoute('app_default_hello');
        }

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $user->setPassword(
                $hasher->hashPassword($user, $form->get('plainPassword')->getData())
            );

            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', 'User successfully registered');
            return $this->redirectToRoute('app_default_hello');
        }

        return $this->render('User/register.html.twig', [
            'userForm' => $form
        ]);
    }

    #[Route('/logout')]
    public function logout() {
        //logout
    }

}