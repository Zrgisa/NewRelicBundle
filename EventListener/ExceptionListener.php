<?php

namespace Zrgisa\NewRelicBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof HttpExceptionInterface) {
            return; // we should only log non-http exceptions
        }

        if (!extension_loaded('newrelic')) {
            return; // the extension is not loaded
        }

        newrelic_notice_error(null, $exception);
    }
}
