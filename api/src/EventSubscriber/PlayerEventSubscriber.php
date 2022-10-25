<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Player;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class PlayerEventSubscriber implements EventSubscriberInterface
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordEncoder)
    {
        $this->passwordHasher = $userPasswordEncoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['hashPasswordPerhaps', EventPriorities::POST_VALIDATE],
        ];
    }

    public function hashPasswordPerhaps(ViewEvent $event)
    {
        $user = $event->getControllerResult();

        $isWriting = in_array($event->getRequest()->getMethod(), [
            Request::METHOD_POST,
            Request::METHOD_PUT,
            Request::METHOD_PATCH,
        ]);

        if ($user instanceof Player && $user->getPassword() && $isWriting) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        }
    }

}
