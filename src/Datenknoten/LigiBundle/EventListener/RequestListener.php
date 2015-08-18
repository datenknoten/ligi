<?php

namespace Datenknoten\LigiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Intl\Intl;

class RequestListener
{
    public function setLocale(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }
        $request = $event->getRequest();
        $browser_locale = $request->getPreferredLanguage();

        $session_locale = $request->getSession()->get('_locale',null);
        if ($session_locale) {
            $request->setLocale($session_locale);
        } else {
            $request->getSession()->set('_locale', $browser_locale);
            $request->setLocale($browser_locale);
        }
        \Locale::setDefault($request->getSession()->get('_locale',null));
    }
}
