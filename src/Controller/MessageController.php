<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/message')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'app_message_index', methods: ['GET'])]
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->render('message/list.html.twig', [
            'messages' => $messageRepository->findByTarget($this->getUser()),
            'messagesSent' => $messageRepository->findBy(['sender' => $this->getUser()])
        ]);
    }

    // Pour rendre un paramètre utilisant le paramconverter optionnel, il faut ajouter une valeur par défaut dans la route :
    #[Route('/new/{id<\d+>}/{reply}', name: 'app_message_new', defaults: ['id' => null, 'reply' => null], methods: ['GET', 'POST'])]
    public function new(?Message $oldMessage, ?string $reply, Request $request, MessageRepository $messageRepository): Response
    {
        $message = new Message();
        // Generation d'un message vide


        //si on a un ancien message (reply)
        if ($oldMessage) {
            // ajout de RE: dans le sujet
            if (!str_starts_with($oldMessage->getSubject(), 'RE: ')) {
                $message->setSubject('RE: ' . $oldMessage->getSubject());
            }
            else {
                $message->setSubject($oldMessage->getSubject());
            }

            // ajout des sauts de ligne et de la barre dans le body
            $message->setBody("\n\n\n____________\n" . $oldMessage->getBody());

            // ajout des ancien destinataires si demandé (paramètre target)
            if ($reply == 'all') {
                $message->setTargets($oldMessage->getTarget());
            }

            //ajout de l'expedirteur en target
            $message->addTarget($oldMessage->getSender());
        }

        //creation du formulaire via le nouveau message généré (vide si pas de reply)
        $form = $this->createForm(MessageType::class, $message);

        // form->handleRequest() sert a récupérer et écraser les info du message avec ceux du formulaire
        $form->handleRequest($request);

        // si formulaire soumi et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // ajout de l'utilisateur courant comme expediteur
            $message->setSender($this->getUser());

            //insertion en db
            $messageRepository->save($message, true);

            //redirection
            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('message/create.html.twig', [
            'message' => $message,
            'form' => $form,
            'title' => "New message"
        ]);
    }

    #[Route('/{id}', name: 'app_message_show', methods: ['GET'])]
    public function show(Message $message, ManagerRegistry $doctrine): Response
    {
        //On change l'état en 'lu'
        $message->setState(true);
        $doctrine->getManager()->flush();
        return $this->render('message/read.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, MessageRepository $messageRepository): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setState(false);
            $message->setCreatedAt(new \DateTimeImmutable());
            $messageRepository->save($message, true);

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('message/create.html.twig', [
            'message' => $message,
            'form' => $form,
            'title' => "Edit message"
        ]);
    }

    #[Route('/{id}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, MessageRepository $messageRepository): Response
    {
        $messageRepository->remove($message, true);
        return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
    }

    public function notif (MessageRepository $rep): Response
    {
        return $this->render('parts/_nbMessages.html.twig', [
            'nb' => count($rep->findByTarget($this->getUser(), true))
        ]);
    }
}
