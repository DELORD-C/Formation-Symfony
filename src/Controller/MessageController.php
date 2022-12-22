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
        ]);
    }

    #[Route('/new', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MessageRepository $messageRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($this->getUser());
            $messageRepository->save($message, true);

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
