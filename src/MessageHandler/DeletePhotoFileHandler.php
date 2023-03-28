<?php

namespace App\MessageHandler;

use App\Message\DeletePhotoFile;
use App\Photo\PhotoFileManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler( fromTransport: 'async', priority: 10)]
class DeletePhotoFileHandler
{
    private $photoManager;

    public function __construct(PhotoFileManager $photoManager)
    {
        $this->photoManager = $photoManager;    
    }

    public function __invoke(DeletePhotoFile $deletePhotoFile)
    {
        $this->photoManager->deleteImage($deletePhotoFile->getFileName());
    }
}
