<?php

namespace App\Messenger;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;

class AuditMiddleware implements MiddlewareInterface
{

    public function __construct(private LoggerInterface $messengerAuditLogger)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {

        if (!$envelope->last(UniqueIdStamp::class)) {
            $envelope = $envelope->with(new UniqueIdStamp());
        }
        $stamp = $envelope->last(UniqueIdStamp::class);

        $context = [
            'id' => $stamp->getUniqueId(),
            'class' => get_class($envelope->getMessage()),
        ];

        $envelope = $stack->next()->handle($envelope, $stack);

        if ($envelope->last(ReceivedStamp::class)) {
            $this->messengerAuditLogger->info("[{id}] Received {class}", $context);
        } elseif ($envelope->last(SentStamp::class)) {
            $this->messengerAuditLogger->info("[{id}] Sent {class}", $context); 
        } else {
            $this->messengerAuditLogger->info("[{id}] Sync {class}", $context); 
        }

        return $envelope;

    }
}
