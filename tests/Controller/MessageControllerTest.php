<?php
declare(strict_types=1);

namespace Controller;

use App\Message\SendMessage;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

class MessageControllerTest extends WebTestCase
{
    use InteractsWithMessenger;
    
    public function testListReturnsJsonResponse(): void
    {
        $client = static::createClient();
        $client->request('GET', '/messages?status=sentt');

        // Ensure response content is not false and is a valid JSON string
        $responseContent = $client->getResponse()->getContent();
        $this->assertNotFalse($responseContent);
        $this->assertJson($responseContent);
    }
    
    function test_that_it_sends_a_message(): void
    {
        $client = static::createClient();
        $client->request('GET', '/messages/send', [
            'text' => 'Hello World',
        ]);

        $this->assertResponseIsSuccessful();
        // This is using https://packagist.org/packages/zenstruck/messenger-test
        $this->transport('sync')
            ->queue()
            ->assertContains(SendMessage::class, 1);
    }
}