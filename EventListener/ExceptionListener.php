<?php

namespace Zrgisa\NewRelicBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    private $thrownAnError = false;

    public function __construct()
    {
        $this->thrownAnError = false;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($this->thrownAnError) {
            return; // do not throw more than one error, since it is going to be overwritten
        }

        $exception = $event->getException();

        if ($exception instanceof HttpExceptionInterface) {
            return; // we should only log non-http exceptions
        }

        if (!extension_loaded('newrelic')) {
            return; // the extension is not loaded
        }

        newrelic_notice_error(null, $exception);
        $this->thrownAnError = true;
    }
}
