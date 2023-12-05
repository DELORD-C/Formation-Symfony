<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Route globale pour le controller, toutes les routes du controller commenceront par /post
#[Route('/post')]
class PostController extends AbstractController {
    // Route de la méthode
    #[Route('/create')]
    // On utilise l'Autowiring* pour accéder aux objets Request et EntityManagerInterface
    public function create (Request $request, EntityManagerInterface $em): Response
    {

        // On créé un Post vide
        $post = new Post();

        // On créé un formulaire vide CF src/Form/PostType.php
        $form = $this->createForm(PostType::class, $post);

        // On passe la requête à notre formulaire : Symfony va associer les éventuelles
        // données envoyées dans la requête ($_POST & $_GET) avec les champs du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Si oui, on récupère les données du formulaire envoyé pour créer un objet Post
            $post = $form->getData();
            // On demande à l'entityManager d'inssérer le nouveau Post dans la base de donnée
            $em->persist($post);
            // On applique les modifications
            $em->flush();

            // On redirige l'utilisateur vers la liste des post
            return $this->redirectToRoute('app_post_list');
        }

        // Si pas de formulaire envoyé, on affiche la page create.html.twig en lui
        // passant en paramètre notre formulaire vide
        return $this->render('Post/create.html.twig', [
            'postForm' => $form
        ]);
    }

    #[Route('/list')]
    public function list (PostRepository $rep): Response
    {
        // On récupère tous nos post grâce au repository
        $posts = $rep->findAll();

        // On passe nos posts en paramètre à notre vue
        return $this->render('Post/list.html.twig', [
            'posts' => $posts
        ]);
    }

    // On demande un paramètre {post} dans notre route
    #[Route('/read/{post}')]
    // En précisant le type de $post, Symfony essayera de convertir le paramètre {post}
    // de notre route en objet de type Post (ParamConverter)
    public function read (Post $post): Response
    {
        return $this->render('Post/read.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/delete/{post}')]
    public function delete (Post $post, EntityManagerInterface $em): Response
    {
        // On demande à l'entityManager de supprimer le Post
        $em->remove($post);
        // On applique les modifs
        $em->flush();
        // On redirige l'utilisateur
        return $this->redirectToRoute('app_post_list');
    }

    #[Route('/update/{post}')]
    public function update (Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em->persist($post);
            $em->flush();
        }

        return $this->render('Post/create.html.twig', [
            'postForm' => $form
        ]);
    }
}

// Autowiring : Lorsque on demande une classe particulière dans les paramètres d'une méthode,
// Symfony essayera de nous fournir un object de cette classe existant si possible, sinon,
// il en instanciera un pour nous