<?php

namespace App\EventSubscriber;

use App\Service\EmailLog;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Email;

class MailerSubscriber implements EventSubscriberInterface
{
    private EmailLog $emailLog;

    public function __construct(EmailLog $emailLog)
    {
        $this->emailLog = $emailLog;    
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MessageEvent::class => 'onMessage',
        ];
    }

    public function onMessage(MessageEvent $event): void
    {
        $message = $event->getMessage();
        if (!$message instanceof Email) {
            return;
        }

        $this->emailLog->log($message);
    }
}