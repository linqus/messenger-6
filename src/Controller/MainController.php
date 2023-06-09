<?php

namespace App\Controller;

use App\Message\Query\GetTotalImageCount;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    #[Route('/')]
    public function homepage(MessageBusInterface $queryBus)
    {
        $envelope = $queryBus->dispatch(new GetTotalImageCount());
        $stamp =$envelope->last(HandledStamp::class);
        return $this->render('main/homepage.html.twig', [
            'imageCount' => $stamp->getResult(),
        ]);
    }
}