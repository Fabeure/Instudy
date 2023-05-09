<?php

namespace App\EventListener;

use App\Repository\NotificationRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class NotificationListener implements EventSubscriberInterface
{
    private $notificationRepository;
    private $twig;

    public function __construct(NotificationRepository $notificationRepository, Environment $twig, Security $security)
    {
        $this->notificationRepository = $notificationRepository;
        $this->twig = $twig;
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $user = $this->security->getUser();
        $notifications = $this->notificationRepository->findBy(['notif_recipient'=>$user]);

        $event->getRequest()->attributes->set('_notifications', $notifications);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }
}