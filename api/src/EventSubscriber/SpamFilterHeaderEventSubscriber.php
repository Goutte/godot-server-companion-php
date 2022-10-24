<?php /** @noinspection PhpUnused */

namespace App\EventSubscriber;

use ApiPlatform\Exception\InvalidArgumentException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SpamFilterHeaderEventSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event): void
    {
        // There's only one endpoint allowing public write access : creating a user
        if (
            $event->getRequest()->getRequestUri() == "/players"
            &&
            $event->getRequest()->getMethod() == Request::METHOD_POST
        ) {
            $key = $event->getRequest()->headers->get('GodotGame', '');
            $hole = getenv("NOSPAM_API_KEY");
            if ( ! isset($hole)) {
                throw new Exception("No NOSPAM_API_KEY env var defined, or perhaps it is empty.");
            }
            if ($key != $hole) {
                throw new InvalidArgumentException("Missing header GodotGame with appropriate value.");
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
