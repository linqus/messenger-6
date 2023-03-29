<?php

namespace App\MessageHandler\Event;

use App\Message\Event\ImagePostDeletedEvent;
use App\Photo\PhotoFileManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class RemoveFileIfImagePostDeleted 
{
    private $photoManager;

    public function __construct(PhotoFileManager $photoManager)
    {
        $this->photoManager = $photoManager;    
    }

    public function __invoke(ImagePostDeletedEvent $event)
    {
        $this->photoManager->deleteImage($event->getFilename());
    }
}