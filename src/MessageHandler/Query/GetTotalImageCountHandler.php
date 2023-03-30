<?php

namespace App\MessageHandler\Query;

use App\Message\Query\GetTotalImageCount;
use App\Repository\ImagePostRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
class GetTotalImageCountHandler
{
    public function __construct(private ImagePostRepository $imagePostRepository)
    {
        
    }

    public function __invoke(GetTotalImageCount $query)
    {
        return $this->imagePostRepository->count([]);
    }
}