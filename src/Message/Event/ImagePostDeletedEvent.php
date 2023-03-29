<?php 

namespace App\Message\Event;

class ImagePostDeletedEvent
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }
}