<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface {
    private string $defaultLocale;
    private array $supportedLanguages;

    public function __construct (string $defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
        $this->supportedLanguages = [
            'en', 'fr'
        ];
    }

    public static function getSubscribedEvents()
    {
        // Ici, on demande au subrsriber de lancer sa méthode updateLocale lors de chaque requête, avec une priorité de 20
        return [
          KernelEvents::REQUEST => [['updateLocale', 20]]
        ];
    }

    //Comme nous sommes dans le contexte d'un subscriber appelé avant les controllers, il faut appeller l'evenement pour accéder à la requète
    public function updateLocale (RequestEvent $event) {
        $request = $event->getRequest();

        $locale = $request->getSession()->get('_locale');

        if (!$locale) {
            // On utilise substr pour récupérer uniquement les deux premiers caractères (ex fr_FR => fr)
            $clientLanguage = substr($request->headers->get('Accept-Language'), 0, 2);
            if ($clientLanguage != null && in_array($clientLanguage, $this->supportedLanguages)) {
                $locale = $clientLanguage;
            }
            else {
                $locale = $this->defaultLocale;
            }
            // On stocke la variable $locale dans la session pour éviter ce processus les fois suivantes
            $request->getSession()->set('_locale', $locale);
        }

        $request->setLocale($locale);
    }
}