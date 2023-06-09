<?php

namespace App\Serializer\Normalizer;

use App\Entity\ImagePost;
use App\Photo\PhotoFileManager;
use Normalizer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ImagePostNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{    
   // use NormalizerAwareTrait;
    private $normalizer;
    private $uploaderManager;
    private $router;

    public function __construct(PhotoFileManager $uploaderManager, UrlGeneratorInterface $router)
    {
        $this->uploaderManager = $uploaderManager;
        $this->router = $router;
        //$this->normalizer = $normalizer;
    }

    /**
     * @param ImagePost $imagePost
     */
    public function normalize($imagePost, $format = null, array $context = array()): array
    {
        $data = $this->normalizer->normalize($imagePost, $format, $context);

        // a custom, and therefore "poor" way of adding a link to myself
        // formats like JSON-LD (from API Platform) do this in a much
        // nicer and more standardized way
        $data['@id'] = $this->router->generate('get_image_post_item', [
            'id' => $imagePost->getId(),
        ]);
        $data['url'] = $this->uploaderManager->getPublicPath($imagePost);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        //return false;
        return $data instanceof ImagePost;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    public function setNormalizer($normalizer)
    {
        $this->normalizer = $normalizer;
    }
}
