<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserUpdateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[Route('/register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em
    ): Response
    {
        $user = new User;

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_user_login');
        }

        return $this->render('user/register.html.twig', [
            'userForm' => $form    
        ]);
    }

    #[Route('/login')]
    public function login (AuthenticationUtils $auth): Response
    {
        return $this->render('User/login.html.twig', [
            'last_username' => $auth->getLastUsername(),
            'error' => $auth->getLastAuthenticationError()
        ]);
    }

    #[Route('/logout')]
    public function logout (): Response {
        throw new \Exception('This code should not be reached, did you forget to add logout path in security.yaml ?');
    }

    #[Route('/admin/user/list')]
    public function list(UserRepository $rep) {
        $users = $rep->findAll();
        return $this->render('User/list.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/admin/user/update/{user}')]
    public function update(User $user, Request $request, EntityManagerInterface $em) {
        $form = $this->createForm(UserUpdateType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if ($user == $this->getUser() && !in_array('ROLE_ADMIN', $user->getRoles())) {
                $roles = $user->getRoles();
                $roles[] = 'ROLE_ADMIN';
                $user->setRoles($roles);
            }

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_user_update', ['user' => $user->getId()]);
        }

        return $this->render('User/update.html.twig', [
            'userForm' => $form,
            'user' => $user
        ]);
    }

    #[Route('/admin/user/delete/{user}')]
    public function delete(User $user, EntityManagerInterface $em) {
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_user_list');
    }
}
