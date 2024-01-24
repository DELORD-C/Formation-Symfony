<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[Route('/register')]
    function register (
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em
    ): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($hasher->hashPassword($user, $user->getRawPassword()));
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', 'User successfully registered.');

        }

        return $this->render('user/register.html.twig', [
            'userForm' => $form
        ]);
    }

    #[Route('/login')]
    function login (AuthenticationUtils $auth): Response
    {
        return $this->render('user/login.html.twig', [
            'last_username' => $auth->getLastUsername(),
            'error' => $auth->getLastAuthenticationError()
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/logout')]
    function logout (): void
    {
        throw new Exception('Did you forget to enable logout in security.yaml ?');
    }

}