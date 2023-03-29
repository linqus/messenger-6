<?php


namespace App\MessageHandler;

use App\Message\DeleteImagePost;
use App\Message\Event\ImagePostDeletedEvent;
use App\Repository\ImagePostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler()]
class DeleteImagePostHandler
{
    private $entityManager;
    private $eventBus;
    private $imagePostRepository;

    public function __construct(EntityManagerInterface $entityManager, ImagePostRepository $imagePostRepository, MessageBusInterface $eventBus)
    {
        $this->entityManager = $entityManager;
        $this->eventBus = $eventBus;
        $this->imagePostRepository = $imagePostRepository;
    }

    public function __invoke(DeleteImagePost $deleteImagePost)
    {
        $imagePostId = $deleteImagePost->getImagePostId();
        $imagePost = $this->imagePostRepository->find($imagePostId);

        $this->entityManager->remove($imagePost);
        $this->entityManager->flush();

        $event = new ImagePostDeletedEvent($imagePost->getFilename());

        $this->eventBus->dispatch($event,[
            
        ]);
        //$message = new DeletePhotoFile($imagePost->getFilename());
        //$this->messageBus->dispatch($message);
    }
}