<?php // src/EventSubscriber/LocaleSubscriber.php

/**
 * Description of LocaleSubscriber
 *
 * @author NUR HIDAYAT
 */

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface{
    
    private $defaultLocale;

    public function __construct($defaultLocale = 'id')
    {
        $this->defaultLocale = $defaultLocale;
    }
    
    
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }
        
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        }else{
            $request->getSession()->set('_locale', $this->defaultLocale);
        }
        $request->setDefaultLocale($request->getSession()->get('_locale', $this->defaultLocale));
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 21)),
        );
    }
}
