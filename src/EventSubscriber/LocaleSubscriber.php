<?php

namespace App\EventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface {
    public static function getSubscribedEvents(): array
    {
        // On demande au subscriber de lancer sa méthode updateLocale lors de chaque requête, avec une priorité de 20
        return [
            KernelEvents::REQUEST => [
                ['updateLocale', 20]
            ]
        ];
    }

    // étant donné que nous sommes dans le contexte d'un eventsubscriber (avant initialisation de la majeur partie des composants), nous devons récupérer l'évènement afin d'accéder à la requête
    public function updateLocale (RequestEvent $event):void
    {
        // On récupère larequête dans l'évènement
        $request = $event->getRequest();

        // On récupère la locale stockée dans la session (ou null si jamais elle n'existait pas)
        $sessionLocale = $request->getSession()->get('_locale');

        // Si pas de locale dans la session
        if (!$sessionLocale) {
            // On récupère la langue du navigateur
            $sessionLocale = substr($request->headers->get('Accept-Language'), 0, 2);
        }

        // Si la langue n'est pas prise ne compte
        if (!in_array($sessionLocale, ['fr', 'en', 'es'])) {
            // On utilise en par défaut
            $sessionLocale = 'en';
        }

        // On stocke notre nouvelle variable dans la session
        $request->getSession()->set('_locale', $sessionLocale);

        // On applique notre locale à notre application
        $request->setLocale($sessionLocale);
    }
}