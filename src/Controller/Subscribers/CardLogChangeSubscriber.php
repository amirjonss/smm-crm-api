<?php

declare(strict_types=1);

namespace App\Controller\Subscribers;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Component\Board\MercurePublisher;
use App\Entity\CardLog;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CardLogChangeSubscriber implements EventSubscriberInterface
{
    public function __construct(private MercurePublisher $mercurePublisher)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onWrite', EventPriorities::POST_WRITE],
        ];
    }

    public function onWrite(ViewEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->getMethod() !== Request::METHOD_POST) {
            return;
        }

        $log = $event->getControllerResult();

        if (!$log instanceof CardLog) {
            return;
        }

        $this->mercurePublisher->publishCardLogAdded($log);
    }
}
