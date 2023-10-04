<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/create')]
    public function create(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    'azerty'
                )
            );
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', 'User successfully created.');
            return $this->redirectToRoute('app_admin_list');
        }

        return $this->render('Admin/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/list')]
    public function list(UserRepository $rep): Response
    {
        $users = $rep->findAll();

        return $this->render('Admin/list.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/update/{user}')]
    public function update(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $superadmin = in_array('ROLE_SUPERADMIN', $user->getRoles());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($superadmin) {
                $user->addRole('ROLE_SUPERADMIN');
            }
            $em->flush();
            return $this->redirectToRoute('app_admin_update', ['user' => $user->getId()]);
        }

        return $this->render('Admin/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{user}')]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("app_admin_list");
    }
}