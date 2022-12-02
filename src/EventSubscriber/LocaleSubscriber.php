<?php

namespace App\EventSubscriber;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface {
    private string $defaultLocale;
    private array $supportedLanguages;

    public function __construct(string $defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
        $this->supportedLanguages = [
            'en', 'fr'
        ];
    }

    #[ArrayShape([KernelEvents::REQUEST => "array[]"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['updateLocale', 20]]
        ];
    }

    public function updateLocale (RequestEvent $event) {
        $request = $event->getRequest();

        if ($request->get('localeSwitch')) {
            $request->getSession()->set('_locale', $request->get('localeSwitch'));
        }

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