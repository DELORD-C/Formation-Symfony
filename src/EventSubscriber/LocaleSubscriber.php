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
            KernelEvents::REQUEST => [['updateLocale', 20]]
        ];
    }

    // étant donné que nous sommes dans le contexte d'un EventSubscriber, nous devons récupérer l'évènement afin d'accéder à la requête
    public function updateLocale (RequestEvent $event): void
    {
        // On récupère la requête dans l'évènement
        $request = $event->getRequest();

        // On récupère la locale stockée dans la session (ou null)
        $locale = $request->getSession()->get('_locale');

        // Si on n'avait pas de locale dans la session
        if (!$locale) {
            // On tente de récupérer la langue du navigateur
            $clientLanguage = substr($request->headers->get('Accept-Language'), 0, 2);

            // Si la langue du navigateur est prise en charge
            if (in_array($clientLanguage, ['fr', 'en', 'es'])) {
                // On la stocke dans la variable locale
                $locale = $clientLanguage;
            }
            else {
                // Sinon on utilise la locale par défaut
                $locale = 'en';
            }

            // On stocke la variable locale dans la session
            $request->getSession()->set('_locale', $locale);
        }

        // On applique la locale sur notre application
        $request->setLocale($locale);
    }
}