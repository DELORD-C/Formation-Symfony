<?php

namespace App\Subscriber;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSetterSubscriber implements EventSubscriberInterface
{
    public array $allowedLocales;

    function __construct() {
        //The first one is default
        $this->allowedLocales = [
            'en',
            'fr'
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!$locale = $request->getSession()->get('_locale')) {
            $clientLanguage = substr($request->headers->get('Accept-Language'), 0, 2);
            $locale = $clientLanguage;
        }

        if ($locale != null && in_array($locale, $this->allowedLocales)) {
            $request->setLocale($locale);
        } else {
            $request->setLocale($this->allowedLocales[0]);
        }
    }

    #[ArrayShape([KernelEvents::REQUEST => "array[]"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 25]]
        ];
    }
}