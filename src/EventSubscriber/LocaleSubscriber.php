<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface {
    private $defaultLocale;
    private $supportedLanguages;

    public function __construct(string $defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
        $this->supportedLanguages = [
            'en', 'fr'
        ];
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['updateLocale', 20]]
        ];
    }

    public function updateLocale (RequestEvent $event) {
        $request = $event->getRequest();

        if (!$locale = $request->getSession()->get('_locale')) {
            $clientLanguage = substr($request->headers->get('Accept-Language'), 0, 2);
            if ($clientLanguage != null && in_array($clientLanguage, $this->supportedLanguages)) {
                $locale = $clientLanguage;
            } else {
                $locale = $this->defaultLocale;
            }
            $request->getSession()->set('_locale', $locale);
        }

        $request->setLocale($locale);
    }
}