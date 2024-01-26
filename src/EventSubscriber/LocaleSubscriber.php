<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['updateLocale', 20]
            ]
        ];
    }

    public function updateLocale(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $sessionLocale = $request->getSession()->get('_locale');

        if (!$sessionLocale) {
            $sessionLocale = substr($request->headers->get('Accept-Language'), 0, 2);
        }

        if (!in_array($sessionLocale, ['fr', 'en', 'es'])) {
            $sessionLocale = 'en';
        }

        $request->getSession()->set('_locale', $sessionLocale);

        $request->setLocale($sessionLocale);
    }
}