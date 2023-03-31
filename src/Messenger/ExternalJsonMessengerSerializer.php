<?php

namespace App\Messenger;

use App\Message\Command\LogEmoji;
use GuzzleHttp\Psr7\Message;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\MessageDecodingFailedStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class ExternalJsonMessengerSerializer implements SerializerInterface
{

    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'];
        $headers = $encodedEnvelope['headers'];

        $data = json_decode($body,true);

        if (null === $data) {
            throw new MessageDecodingFailedException('Ivalid JSON');
        }

        if (!array_key_exists('emoji', $data)) {
            throw new MessageDecodingFailedException('No expected "emoji" in JSON');
        };

        $idx = $data['emoji'];

        $message = new LogEmoji($idx);

        $envelope = new Envelope($message);
        $envelope = $envelope->with(new BusNameStamp('command.bus'));
        return $envelope;
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