<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserUpdateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/list')]
    function list (UserRepository $rep): Response
    {
        return $this->render('admin/user/list.html.twig', [
            'users' => $rep->findAll()
        ]);
    }

    #[Route('/update/{user}')]
    #[IsGranted('ADMINUPDATE', 'user')]
    function update (User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserUpdateType::class, $user, [
            'currentUser' => $this->getUser(),
            'updatedUser' => $user
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('notice', 'User successfully updated.');
            return $this->redirectToRoute('app_admin_user_list');
        }

        return $this->render('default/form.html.twig', [
            'form' => $form,
            'title' => 'Edit user'
        ]);
    }
}