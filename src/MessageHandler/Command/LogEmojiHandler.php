<?php

namespace App\MessageHandler\Command;

use App\Message\Command\LogEmoji;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class LogEmojiHandler 
{
    private static $emojis = [
        ':-)',
        ';-)',
        '8*)',
        ':-/',
        '%-)'
    ];

    public function __construct(private LoggerInterface $logger)
    {
    }
    public function __invoke(LogEmoji $command)
    {

        $index = $command->getEmojiIndex();
        $emoji = self::$emojis[$index] ?? self::$emojis[0];

        $this->logger->info('incoming emoji nr {nr} which is {emoji}',[
            'nr' => $index,
            'emoji' => $emoji,
        ]);
    }
}