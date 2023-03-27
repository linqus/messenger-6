<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImagePostRepository")
 */
class ImagePost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    #[Groups(['image:output'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['image:output'])]
     private $filename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['image:output'])]
    private $originalFilename;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['image:output'])]
    private $ponkaAddedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['image:output'])]
    // #[Context([DateTimeNormalizer::FORMAT_KEY => \DateTime::RFC3339])]
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    public function setOriginalFilename(string $originalFilename): self
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    public function getPonkaAddedAt(): ?\DateTimeInterface
    {
        return $this->ponkaAddedAt;
    }

    public function markAsPonkaAdded(): self
    {
        $this->ponkaAddedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setPonkaAddedAt(?\DateTimeInterface $ponkaAddedAt): self
    {
        $this->ponkaAddedAt = $ponkaAddedAt;

        return $this;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
