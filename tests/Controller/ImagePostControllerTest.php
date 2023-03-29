<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImagePostControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();

        $uploadedFile = new UploadedFile(
            __DIR__.'/../fixtures/avenue.jpg',
            'ryan-fabien.jpg'
        );
        $client->request('POST', '/api/images', [], [
            'file' => $uploadedFile
        ]);
        //dd($client->getResponse()->getContent());

        $this->assertResponseIsSuccessful();

        $transport = self::getContainer()->get('messenger.transport.async_high_priority');

        $this->assertCount(1, $transport->get());

        //dd($transport);

    }
}
