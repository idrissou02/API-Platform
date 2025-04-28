<?php 
namespace App\Services;

use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\Pret;
use Symfony\Component\HttpFoundation\Request;

class PretSubscriber implements EventSubscriberInterface
{
    public function __construct(TokenStorageInterface $token)
    {
        $this->token=$token;
    }
    public static function getSubscribedEvents()
    {
        return [
            kernelEvents::VIEW => ['getAuthenticatedUser', EventPriorities::PRE_WRITE]
        ];
    }

    public function onKernelRequest(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $adherent = $this->token->getToken()->getUser();
        if ($entity instanceof Pret && $method === Request::METHOD_POST) {
            $entity->setAdherent($adherent);
        }
        return;
    }
}