<?php

namespace App\Messenger;

use App\Message\Command\LogEmoji;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class ExternalJsonMessengerSerializer implements SerializerInterface
{

    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'];
        $headers = $encodedEnvelope['headers'];

        $data = json_decode($body,true);

        $idx = $data['emoji'];

        $message = new LogEmoji($idx);
        return new Envelope($message);
    }

    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        if ($message instanceof LogEmoji) {
            $data['emoji'] = $message->getEmojiIndex();
        } else {
            throw new \Exception('Unsupported message!');
        };

        $stamps = $envelope->all();
        $allStamps = [];

        foreach ($stamps as $stamp) {
            $allStamps = array_merge($allStamps, $stamp);
        }

        return [
            'body' => json_encode($data),
            'headers' => [
                'stamps' => serialize($allStamps),
            ],
        ];
    }

}