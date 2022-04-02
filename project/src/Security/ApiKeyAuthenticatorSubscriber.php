<?php

namespace App\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiKeyAuthenticatorSubscriber implements EventSubscriberInterface
{
    private string $apiKey;
    private string $apiValue;

    public function __construct(string $apiKey, string $apiValue)
    {
        $this->apiKey = $apiKey;
        $this->apiValue = $apiValue;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        $token = $event->getRequest()->headers->get($this->apiKey);
        if ($this->apiValue !== $token) {
            throw new AccessDeniedHttpException('This action needs a valid token!');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
