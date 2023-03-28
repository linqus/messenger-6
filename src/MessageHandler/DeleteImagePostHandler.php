<?php


namespace App\MessageHandler;

use App\Entity\ImagePost;
use App\Message\DeleteImagePost;
use App\Message\DeletePhotoFile;
use App\Repository\ImagePostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler( fromTransport: 'async', priority: 10)]
class DeleteImagePostHandler
{
    private $entityManager;
    private $messageBus;
    private $imagePostRepository;

    public function __construct(EntityManagerInterface $entityManager, ImagePostRepository $imagePostRepository, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
        $this->imagePostRepository = $imagePostRepository;
    }

    public function __invoke(DeleteImagePost $deleteImagePost)
    {
        $imagePostId = $deleteImagePost->getImagePostId();
        $imagePost = $this->imagePostRepository->find($imagePostId);
        $this->entityManager->remove($imagePost);
        $this->entityManager->flush();

        $message = new DeletePhotoFile($imagePost->getFilename());
        $this->messageBus->dispatch($message);
    }
}