<?php

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSetterSubscriber implements EventSubscriberInterface
{

    public function onKernelRequest(RequestEvent $event) {

        $request = $event->getRequest();

        $allowedLocales = ['en', 'fr'];
        $clientLanguage = substr($request->headers->get('Accept-Language'), 0, 2);

        if ($clientLanguage != null && in_array($clientLanguage, $allowedLocales)) {
            $request->setLocale($clientLanguage);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 25]]
        ];
    }
}