<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Player;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class PlayerEventSubscriber implements EventSubscriberInterface {

    private UserPasswordHasherInterface $passwordEncoder;

//    public function __construct(PasswordHasherInterface $userPasswordEncoder)
    public function __construct(UserPasswordHasherInterface $userPasswordEncoder)
    {
        $this->passwordEncoder = $userPasswordEncoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setPassword', EventPriorities::POST_VALIDATE],
        ];
    }

    public function setPassword(ViewEvent $event)
    {
        $user = $event->getControllerResult();

        if ($user instanceof Player && $user->getPassword()) {
            $password = $this->passwordEncoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
        }
    }

}
